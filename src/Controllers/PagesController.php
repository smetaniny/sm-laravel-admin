<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PagesController extends BaseAdminController
{
    /**
     * Показываем страницы
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //Получение коллекции
        return $this->getPaginatedJsonResponse(Pages::query(), $request);
    }

    /**
     * Показываем конкретную страницу
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $result = Pages::find($id);
        $result::get()->toTree();
        return response()->json($result);
    }

    /**
     * Создание новой страницы
     *
     * @throws \Exception
     */
    public function store(PagesStoreRequest $request)
    {
        $result = $this->storeUpdate($request, new Pages(), new Menu(), new AliasCategories(), 'store');

        // Возвращаем дерево всех страниц
        return $this->getJsonSuccessResponse(
            $result,
            'Успешное создание',
            true
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PagesUpdateRequest $request, int $id)
    {
        //Добавляем / изменяем
        $page = Pages::findOrFail($id);
        $menu = Menu::where('page_id', $page->id)->first() ?? new Menu();
        $aliasCategories = AliasCategories::where('slug', $page->alias)->first() ?? new AliasCategories();

        $result = $this->storeUpdate($request, $page, $menu, $aliasCategories, 'update');
        // Возвращаем дерево всех страниц
        return $this->getJsonSuccessResponse($result, 'Успешное обновление', true);
    }

    /**
     * @throws \Exception
     */
    private function storeUpdate(
        PagesBaseRequest $request,
        Pages            $page,
        Menu             $menu,
        AliasCategories  $aliasCategory,
                         $flag = "store"
    )
    {
        // Получение текущего администратора
        $adminUser = auth()->guard('admin')->user();
        // Получение всех данных из запроса
        $data = $request->all();
        // Получение родительских элементов из запроса
        $parentItems = Pages::find($data['parent_id']) ?? new Pages(['id' => 1]);
        // Получение id шаблона
        $templateId = Templates::find($data['template_id'])->id ?? 1;
        // Получение id категории
        $categoryId = Categories::find($data['category_id'])->id ?? 1;
        // Получаем родительскую страницу, на которую будет ссылаться новая страница
        $this->parentPage = Pages::where('alias', $parentItems->alias)->first();
        // Получение id тегов из запроса
        $tagIds = $data['tags'] ?? [];
        // Добавление новых тегов, если они указаны
        $newTags = $data['tags_new'] ?? [];
        $alias = $data['alias'] ?? null;
        $with_tv_params_template = $request['with_templates']['with_tv_params_template'] ?? [];
        $template_id = $data['template_id'] ?? 1;

        // Получаем максимальный индекс меню для установки индекса нового элемента
        $menuindex = $data['menuindex'];
        $menuIndex = $menuindex !== null ? $menuindex : Pages::max('menuindex') + 1;
        $page->title = $data['title'] ?? "";
        $page->menutitle = $data['menutitle'] ?? "";
        $page->robots = $data['robots'];
        $page->alias = $alias ?: Str::slug($data['title']);
        $page->description = $data['description'] ?? "";
        $page->meta_keywords = $data['meta_keywords'] ?? null;
        $page->og_title = $data['og_title'] ?? null;
        $page->og_description = $data['og_description'] ?? null;
        $page->og_image = $data['og_image'] ?? null;
        $page->twitter_title = $data['twitter_title'] ?? null;
        $page->twitter_description = $data['twitter_description'] ?? null;
        $page->twitter_image = $data['twitter_image'] ?? null;
        $page->menuindex = $menuIndex;
        $page->language = 'ru';
        $page->parent_id = $this->parentPage?->id;
        $page->author_id = $adminUser?->id;
        $page->is_open = $data['is_open'] ?? false;
        $page->is_published = $data['is_published'] ?? false;
        $page->is_visible_url = $data['is_visible_url'] ?? false;
        $page->template_id = $templateId;
        $page->category_id = $categoryId;
        $page->canonical_url = $data['canonical_url'] ?? PageHelper::generatePageUrl(Str::slug($data['title']));
        $page['content_js'] = json_encode($data['content_js']);
        $page['content'] = !empty($data['content_js']['blocks']) ? EJSParser::parse($data['content_js'])->toHtml() : null;
        $page->save();

        // Создание новой записи в таблице menu
        $withMenu = $this->parentPage?->withMenu->first();
        $menu->parent_id = $withMenu != null ?? $withMenu->id;
        $menu->slug = $page->alias;
        $menu->page_id = $page->id;
        $menu->title = $page->title;
        $menu->is_enabled = true;
        $menu->order = Menu::max('order') + 1;
        $menu->save();


        // Создание новой записи в таблице AliasCategories
        $aliasCategory->category_id = $categoryId;
        $aliasCategory->slug = $page->alias;
        $aliasCategory->save();

        TemplatesTvParams::where('page_id', $page->id)
            ->where('template_id', '!=', $template_id)
            ->delete();


        foreach ($with_tv_params_template as $v) {
            TemplatesTvParams::updateOrCreate(
                [
                    'page_id' => $v['pivot']['page_id'],
                    'template_id' => $v['pivot']['template_id'],
                    'tv_param_id' => $v['pivot']['tv_param_id'],
                ],
                [
                    'value' => $v['pivot']['value'],
                ],
                $v
            );
        }


        foreach ($newTags as $v) {
            $tvParams = Tags::updateOrCreate(['name' => $v]);
            $tagIds[] = $tvParams->id;
        }

        //Связывание созданной страницы с тегами
        $page->withPagesTags()->sync($tagIds);

        return $page;
    }

    /**
     * Удаление страницы
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function destroy(int $id)
    {
        $page = Pages::findOrFail($id);
        // Удаление модели Pages
        $result = $page->forceDelete();

        return response()->json(['success' => $result, 'message' => 'Успешное удаление']);
    }
}
