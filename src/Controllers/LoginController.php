<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends BaseAdminController
{

    /**
     * Проверка авторизации пользователя
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            session()->save();
            return response()->json([
                'status' => true,
                'user' => auth()->guard('admin')->user(),
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('adminLogin');
    }

}
