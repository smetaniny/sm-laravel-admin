<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use App\Http\Requests\Admin\TemplatesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Smetaniny\SmLaravelAdmin\Models\Template;

class TemplatesController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        //Получение коллекции
        return $this->getPaginatedJsonResponse(Template::query(), $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TemplatesRequest $request
     *
     * @return JsonResponse
     */
    public function store(TemplatesRequest $request): JsonResponse
    {
        $data = $request->all();
        $data['alias'] = Str::slug($data['name']);

        $result = new Template($data);
        $result->save();

        return $this->getJsonSuccessResponse($result, 'Успешное создание', true);
    }

    /**
     * Display the specified resource.
     *
     *
     * @param Template $template
     *
     * @return JsonResponse
     */
    public function show(Template $template): JsonResponse
    {
        return response()->json($template);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param TemplatesRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(TemplatesRequest $request, Template $template): JsonResponse
    {
        $data = $request->all();
        $template->update($data);

        return $this->getJsonSuccessResponse($template, 'Успешное обновление', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = Template::findOrFail($id);
        $result->delete();
        return response()->json(['success' => true, 'message' => 'Успешное удаление']);
    }
}
