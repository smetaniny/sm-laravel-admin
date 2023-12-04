<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use App\Http\Controllers\Helper\EJSParser;
use App\Http\Controllers\Helper\PageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Smetaniny\SmLaravelAdmin\Contracts\ResourceShowInterface;
use Smetaniny\SmLaravelAdmin\Exceptions\SmAdminException;
use Smetaniny\SmLaravelAdmin\Models\AliasCategory;
use Smetaniny\SmLaravelAdmin\Models\Category;
use Smetaniny\SmLaravelAdmin\Models\Menu;
use Smetaniny\SmLaravelAdmin\Models\Page;
use Smetaniny\SmLaravelAdmin\Models\Tag;
use Smetaniny\SmLaravelAdmin\Models\Template;
use Smetaniny\SmLaravelAdmin\Models\TemplateTvParam;
use Smetaniny\SmLaravelAdmin\Requests\PagesBaseRequest;
use Smetaniny\SmLaravelAdmin\Requests\PagesStoreRequest;
use Smetaniny\SmLaravelAdmin\Requests\PagesUpdateRequest;


class PagesController extends BaseAdminController
{
    /**
     * Отображение списка по страницам.
     *
     * @param Request $request
     * @param ResourceShowInterface $resourceShow
     *
     * @return JsonResponse
     */
    public function index(Request $request, ResourceShowInterface $resourceShow): JsonResponse
    {
        // Получение списка страниц с использованием интерфейса для отображения ресурсов
        return $resourceShow
            ->queryBuilder(Page::query(), $request)
            ->sort()
            ->filter()
            ->pagination()
            ->responseJson();
    }

    /**
     * Отображение конкретной страницы.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // Получение иерархического списка страниц по их идентификатору
        $result = Page::find($id);
        $result::get()->toTree();

        return response()->json($result);
    }


    /**
     * Создание новой страницы.
     *
     * @param PagesStoreRequest $request
     *
     * @return JsonResponse
     * @throws SmAdminException
     */
    public function store(PagesStoreRequest $request): JsonResponse
    {
        // Используем метод для создания или обновления страницы, меню и категории псевдонимов
        $result = $this->storeUpdate($request, new Page(), new Menu(), new AliasCategory());

        // Возвращаем успешный JSON-ответ с деревом всех страниц
        return $this->getJsonSuccessResponse(
            $result,
            'Успешное создание',
            true
        );
    }


    /**
     * Обновление страницы.
     *
     * @param PagesUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     * @throws SmAdminException
     */
    public function update(PagesUpdateRequest $request, int $id): JsonResponse
    {
        // Находим существующую страницу, меню и категорию псевдонимов по идентификатору
        $page = Page::findOrFail($id);
        $menu = Menu::where('page_id', $page->id)->first() ?? new Menu();
        $aliasCategory = AliasCategory::where('slug', $page->alias)->first() ?? new AliasCategory();

        // Используем метод для создания или обновления страницы, меню и категории псевдонимов
        $result = $this->storeUpdate($request, $page, $menu, $aliasCategory, 'update');

        // Возвращаем успешный JSON-ответ с деревом всех страниц
        return $this->getJsonSuccessResponse($result, 'Успешное обновление', true);
    }


    /**
     * Создание или обновление страницы, меню и категории псевдонимов.
     *
     * @param PagesBaseRequest $request
     * @param Page $page
     * @param Menu $menu
     * @param AliasCategory $aliasCategory
     * @param string $action
     *
     * @return Page
     * @throws SmAdminException
     */
    private function storeUpdate(
        PagesBaseRequest $request,
        Page $page,
        Menu $menu,
        AliasCategory $aliasCategory,
        string $action = 'create'
    ): Page {
        // Получение данных из запроса
        $data = $request->validated();
        // Получение текущего администратора
        $adminUser = auth()->guard('admin')->user();
        // Получение родительских элементов из запроса
        $parentItems = Page::find($data['parent_id']) ?? new Page(['id' => 1]);
        // Получение id шаблона
        $templateId = Template::find($data['template_id'])->id ?? 1;
        // Получение id категории
        $categoryId = Category::find($data['category_id'])->id ?? 1;
        // Получаем родительскую страницу, на которую будет ссылаться новая страница
        $parentPage = Page::where('alias', $parentItems->alias)->first();
        // Получение id тегов из запроса
        $tagIds = $data['tags'] ?? [];
        // Добавление новых тегов, если они указаны
        $newTags = $data['tags_new'] ?? [];
        $alias = $data['alias'] ?? null;
        $withTvParamsTemplate = $request['with_templates']['with_tv_params_template'] ?? [];
        $templateId = $data['template_id'] ?? 1;

        // Получаем максимальный индекс меню для установки индекса нового элемента
        $menuindex = $data['menuindex'];
        $menuIndex = $menuindex !== null ? $menuindex : Page::max('menuindex') + 1;
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
        $page->parent_id = $parentPage?->id;
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

        // Проверка успешного создания или обновления страницы
        if ($action === 'create' && !$page->wasRecentlyCreated) {
            throw new SmAdminException('Ошибка при создании Page', 500);
        } elseif ($action === 'update' && !$page->wasChanged()) {
            throw new SmAdminException('Ошибка при обновлении Page', 500);
        }

        // Создание новой записи в таблице menu
        $withMenu = $parentPage?->withMenu->first();
        $menu->parent_id = $withMenu != null ?? $withMenu->id;
        $menu->slug = $page->alias;
        $menu->page_id = $page->id;
        $menu->title = $page->title;
        $menu->is_enabled = true;
        $menu->order = Menu::max('order') + 1;
        $menu->save();
        // Проверка успешного создания меню
        if (!$menu->wasRecentlyCreated) {
            throw new SmAdminException('Ошибка при создании Menu', 500);
        }

        // Создание новой записи в таблице AliasCategories
        $aliasCategory->category_id = $categoryId;
        $aliasCategory->slug = $page->alias;
        $aliasCategory->save();
        // Проверка успешного создания категории псевдонимов
        if (!$aliasCategory->wasRecentlyCreated) {
            throw new SmAdminException('Ошибка при создании AliasCategory', 500);
        }

        // Удаление ненужных записей в таблице TemplateTvParam
        TemplateTvParam::where('page_id', $page->id)
            ->where('template_id', '!=', $templateId)
            ->delete();

        // Обновление или создание записей в таблице TemplateTvParam
        foreach ($withTvParamsTemplate as $tvParam) {
            TemplateTvParam::updateOrCreate(
                [
                    'page_id' => $tvParam['pivot']['page_id'],
                    'template_id' => $tvParam['pivot']['template_id'],
                    'tv_param_id' => $tvParam['pivot']['tv_param_id'],
                ],
                [
                    'value' => $tvParam['pivot']['value'],
                ],
                $tvParam
            );
        }

        // Создание новых тегов
        foreach ($newTags as $tagName) {
            $tag = Tag::updateOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }

        // Связывание созданной страницы с тегами
        $page->withPagesTags()->sync($tagIds);

        return $page;
    }

    /**
     * Удаление страницы.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // Находим страницу по идентификатору
        $page = Page::findOrFail($id);

        // Удаляем страницу
        $result = $page->delete();

        // Возвращаем JSON-ответ о результате удаления
        return response()->json(['success' => $result, 'message' => 'Успешное удаление']);
    }

}
