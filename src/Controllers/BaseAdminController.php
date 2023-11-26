<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use App\Http\Controllers\Controller;
use Smetaniny\SmLaravelAdmin\Models\BaseModel;


class BaseAdminController extends Controller
{
    /**
     * Отобращение списка
     *
     * @param $query
     * @param $request
     *
     * @return array
     */
    protected function paginateTable($query, $request)
    {
        $start = (int) $request->input('_start', 0);
        $end = (int) $request->input('_end', 0);
        $sort = $request->only('_order', '_sort');
        $pagination = $request->only('_start', '_end');
        $id = $request->input('id');
        $menu_id = $request->input('menu_id');

        if (isset($sort['_order'], $sort['_sort'])) {
            $query->orderBy($sort['_sort'], $sort['_order']);
        }

        if (isset($pagination['_start'], $pagination['_end'])) {
            $query->skip($pagination['_start'])->take($pagination['_end'] - $pagination['_start']);
        }

        if (!empty($menu_id)) {
            $query->where('id', $menu_id)->orWhere('parent_id', '=', $menu_id);
        }

        $count = $query->count();

        if ($end > 0) {
            $count = min($count, $end - $start + 1);
        } else {
            $count = max($count - $start, 0);
        }

        $contentRange = 'items ' . $start . '-' . ($start + $count - 1) . '/' . $count;

        return [
            'query' => $query,
            'count' => $count,
            'contentRange' => $contentRange,
        ];
    }


    /**
     * Ответ на фронт для index
     *
     * @param $query
     * @param $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getPaginatedJsonResponse($query, $request)
    {
        $result = $this->paginateTable($query, $request);

        return response()->json($result['query']->get())
            ->header('Content-Type', 'application/json')
            ->header('X-Total-Count', $result['count'])
            ->header('Content-Range', $result['contentRange']);
    }


    /**
     * Ответ на фронт для update
     *
     * @param string $message
     * @param bool $status
     * @param BaseModel $result
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonSuccessResponse(BaseModel $result, string $message = '', bool $status = false)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            ...$result->toArray()
        ]);
    }
}
