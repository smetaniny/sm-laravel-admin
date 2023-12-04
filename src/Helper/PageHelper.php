<?php

namespace App\Http\Controllers\Helper;

use App\Models\Admin\Pages;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PageHelper
{
    //Получение страницы по псевдониму
    public function processPage(string $alias)
    {
        // Получаем страницу
        $page = Pages::where('alias', $alias)->first();

        //Если не нашли, выход
        if ($page === null) {
            abort(404);
        } else {
            // Установка значения для robots в зависимости от наличия GET-параметров
            $page->robots = !empty($_GET) ? 'noindex,nofollow' : $page->robots;
        }

        return $page;
    }

    public function getDocLister(Pages $page)
    {
        return Cache::remember('doc_lister_' . $page->id, now()->addDays(15), function () use ($page) {
            $docLister = [];
            // Если есть первая страница, обрабатываем каждую страницу с шаблонами и TV-параметрами
            $filteredItems = $page?->withTvParams->filter(function ($item) use ($page) {
                if ($item->name === "DocLister") {
                    return $page?->id === $item->pivot->page_id;
                }
            });

            $filteredItems->each(function ($item) use (&$docLister) {
                $processedItems = $this->processDocLister($item->pivot->value);
                $docLister = array_merge($docLister, $processedItems);
            });

            return $docLister;
        });
    }

    protected function processDocLister($elements)
    {
        // Извлекаем параметры из элементов DocLister
        $pattern = '/&([^=\n]+)=`([^`]+)`/';
        preg_match_all($pattern, $elements, $matches);
        $params = array_combine($matches[1], $matches[2]);
        // Получаем необходимые параметры
        $exclude = $params['exclude'] ?? "";
        $selectFields = $params['selectFields'] ?? "*";
        //Сколько показать
        $display = $params['display'] ?? null;
        $orderBy = $params['orderBy'] ?? '';
        $orderParts = explode(' ', $orderBy);
        $column = $orderParts[0];
        $direction = $orderParts[1] ?? 'ASC';
        //Массив id для исключения
        $excludeIds = [];
        if (!empty($exclude)) {
            $excludeIds = explode(',', $exclude);
            $excludeIds = array_map('intval', $excludeIds);
        }

        // Находим родительскую запись по идентификатору
        $parentPage = Pages::find($params['parents'] ?? null);

        if ($parentPage) {
            $children = $parentPage->descendants()
                ->whereNotIn('id', $excludeIds)
                ->select(DB::raw($selectFields))
                ->orderBy($column, $direction)
                ->limit($display)
                ->get();

            $visibleChildren = [];
            // Получаем идентификаторы родительских записей
            $parentIds = $children->pluck('parent_id')->unique()->toArray();
            // Получаем все родительские записи одним запросом
            $parents = Pages::whereIn('id', $parentIds)->get();
            foreach ($children as $result) {
                $parentId = $result->parent_id;
                $isVisibleUrl = $result->is_visible_url;
                // Пропускаем элемент, если он не участвует в URL
                if (!$isVisibleUrl) {
                    continue;
                }
                // Формируем URL на основе псевдонима и родительских алиасов
                if ($parentId !== null) {
                    while ($parentId != 0) {
                        // Находим родительскую запись по идентификатору
                        $parent = $parents->where('id', $parentId)->first();
                        if ($parent) {
                            // Пропускаем родителя, если он не участвует в URL
                            if (!$parent->is_visible_url) {
                                break;
                            }
                            $parentId = $parent->parent_id;
                        } else {
                            // Если $parent не существует, выходим из цикла
                            break;
                        }
                    }
                }
                //Формируем URL
                $result->url = $this->generatePageUrl($result->alias);
                // Добавляем элемент в массив видимых детей
                $visibleChildren[] = $result;
            }
            return $visibleChildren;
        } else {
            // Обработка случая, когда родительская запись не найдена
            return [];
        }
    }


    static function generatePageUrl($alias)
    {
        return Cache::remember('page_url_' . $alias, now()->addDays(15), function () use ($alias) {
            $page = Pages::where('alias', $alias)->first();
            $aliasUrl = '';

            if (!$page) {
                return "/";
            }

            $parents = [];
            $parent = $page;

            while ($parent->parent_id !== null) {
                $parent = Pages::find($parent->parent_id);

                if (!$parent) {
                    continue;
                }

                if ($parent->is_visible_url) {
                    $parents[] = $parent;
                }
            }

            if ($page->is_visible_url) {
                $aliasUrl = $page->alias;
            }

            $url = $page->alias;
            $parentAliases = [];

            foreach ($parents as $parent) {
                $parentAliases[] = $parent->alias;
            }

            if (!empty($parentAliases)) {
                $parentAliases = array_reverse($parentAliases);
                $url = implode('/', $parentAliases);
                $url = preg_replace('#/{2,}#', '/', $url . '/' . $aliasUrl);
            }

            return $url;
        });
    }


    protected function schemaOrgDataGet()
    {
        return [
            [
                "@context" => "https://schema.org",
                "@type" => "WebSite",
                "name" => "Smetaniny",
                "alternateName" => "Студия Сметаниных",
                "url" => request()->getSchemeAndHttpHost(),
                "image" => request()->getSchemeAndHttpHost() . "/android-chrome-384x384.png",
                "description" => $this->description,
                "author" => [
                    "@type" => "Person",
                    "name" => "Smetaniny"
                ],
            ],
        ];
    }
}
