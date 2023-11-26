<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TemplatesController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //Получение коллекции
        return $this->getPaginatedJsonResponse(Templates::query(), $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TemplatesRequest $request)
    {
        $data = $request->all();
        $data['alias'] = Str::slug($data['name']);

        $result = new Templates($data);
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
        $result = Templates::find($id);
        return response()->json($result);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param TemplatesRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TemplatesRequest $request, int $id)
    {
        $data = $request->all();
        $pageTemplate = Templates::findOrFail($id);
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
        $result = Templates::findOrFail($id);
        $result->delete();
        return response()->json(['success' => true, 'message' => 'Успешное удаление']);
    }
}
