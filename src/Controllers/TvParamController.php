<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use Illuminate\Http\Request;

class TvParamController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Получение модели
        $query = TvParams::query();
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
     * @return \Illuminate\Http\Response
     */
    public function store(TvParamsRequest $request)
    {
        $result = new TvParams($request->all());
        $templateId = $result->tv_param_template_id;
        $pages = Pages::where('template_id', $templateId)->get();
        $result->save();

        foreach ($pages as $page) {
            TemplatesTvParams::firstOrCreate(
                [
                    'tv_param_id' => $result->id,
                    'template_id' => $templateId,
                    'page_id' => $page->id,
                ],
                [
                    'value' => null,
                ]
            );
        }


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
        $result = TvParams::find($id);
        $templateId = $result->tv_param_template_id;
        $pages = Pages::where('template_id', $templateId)->get();

        foreach ($pages as $page) {
            TemplatesTvParams::firstOrCreate(
                [
                    'tv_param_id' => $result->id,
                    'template_id' => $templateId,
                    'page_id' => $page->id,
                ],
                [
                    'value' => null,
                ]
            );
        }

        return response()->json($result);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $data = $request->all();
        $result = TvParams::findOrFail($id);
        $result->update($data);

        $templateId = $result->tv_param_template_id;
        $pages = Pages::where('template_id', $templateId)->get();

        foreach ($pages as $page) {
            TemplatesTvParams::firstOrCreate(
                [
                    'tv_param_id' => $result->id,
                    'template_id' => $templateId,
                    'page_id' => $page->id,
                ],
                [
                    'value' => null,
                ]
            );
        }

        return $this->getJsonSuccessResponse($result, 'Успешное обновление', true);
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
        $result = TvParams::findOrFail($id);
        $result->delete();
        return response()->json(['success' => true, 'message' => 'Успешное удаление']);
    }
}
