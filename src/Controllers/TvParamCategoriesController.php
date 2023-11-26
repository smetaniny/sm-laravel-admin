<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use Illuminate\Http\Request;

class TvParamCategoriesController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Получение модели
        $query = TvParamCategories::query();
        //Выборка с таблицы
        $result = $this->paginateTable($query, $request);
        //Результат
        return response()->json($result['query']->get())
            ->header('Content-Type', 'application/json')
            ->header('X-Total-Count', $result['count'])
            ->header('Content-Range', $result['contentRange']);
    }


    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TvParamCategoriesRequest $request)
    {
        // Создание записи
        $result = new TvParamCategories($request->all());
        $result->save();

        return $this->getJsonSuccessResponse($result, 'Успешное создание', true);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $result = TvParamCategories::find($id);
        return response()->json($result);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $data = $request->all();
        $pageTemplate = TvParamCategories::findOrFail($id);
        $pageTemplate->update($data);

        return $this->getJsonSuccessResponse($pageTemplate, 'Успешное обновление', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $result = TvParamCategories::findOrFail($id);
        $result->delete();
        return response()->json(['success' => true, 'message' => 'Успешное удаление']);
    }
}
