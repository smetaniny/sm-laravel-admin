<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use Illuminate\Http\JsonResponse;


class AuthVerificationController extends BaseAdminController
{
    /**
     * Проверка авторизации
     *
     */
    public function authVerification(): JsonResponse
    {
        $auth = auth()->guard('admin')->user();

        if (!$auth) {
            session()->flush();
        }

        return response()->json([
            'status' => $auth
        ]);
    }
}
