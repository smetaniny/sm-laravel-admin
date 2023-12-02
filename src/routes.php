<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use Smetaniny\SmLaravelAdmin\Controllers\AuthVerificationController;
use Smetaniny\SmLaravelAdmin\Controllers\CategoriesController;
use Smetaniny\SmLaravelAdmin\Controllers\FilesController;
use Smetaniny\SmLaravelAdmin\Controllers\LoginController;
use Smetaniny\SmLaravelAdmin\Controllers\PagesController;
use Smetaniny\SmLaravelAdmin\Controllers\TagsController;
use Smetaniny\SmLaravelAdmin\Controllers\TemplatesController;
use Smetaniny\SmLaravelAdmin\Controllers\TvParamCategoriesController;
use Smetaniny\SmLaravelAdmin\Controllers\TvParamController;
use Smetaniny\SmLaravelAdmin\Controllers\UrlGetController;
use Smetaniny\SmLaravelAdmin\Models\Page;

//Страница авторизации
//Route::get('/', function () {
//    return Inertia::render('SMAdmin', [
//        'csrf_token' => Session::token(),
//        'success' => Session::get('success'),
//        'canLogin' => (bool) auth()->guard('admin')->user(),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//        'pages' => Page::get()->toTree(),
//    ]);
//})->name('sm-admin-login');

// Пример добавления middleware для сессий в routes.php пакета
Route::middleware(['web'])->group(function () {
//    if (Auth::guard('admin')->attempt(['email' => "admin@example.com", 'password' => "123admin@example.comqwerty"])) {
//        Log::error('Успешная аутентификация');
//    } else {
//        Log::error(' Неудачная аутентификация');
//    }

    // Ваши маршруты здесь
    Route::prefix('/sm-admin')->group(function () {
        Route::get('/', function () {
            return Inertia::render('SMAdmin', [
                'csrf_token' => Session::token(),
                'success' => Session::get('success'),
                'canLogin' => (bool) auth()->guard('admin')->user(),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
                'pages' => Page::get()->toTree(),
            ]);
        })->name('sm-admin');
    });

    //Проверка авторизации
    Route::post('/api/admin/auth_verification', [AuthVerificationController::class, 'authVerification']);
    //Авторизация
    Route::post('/api/admin/login', [LoginController::class, 'login']);
    Route::post('/api/admin/logoutAdmin', [LoginController::class, 'destroy'])->name('logoutAdmin');
    //Получение url
    Route::post('/api/admin/urlGet', [UrlGetController::class, 'urlGet']);
});



//Проверка на авторизацию в админке
Route::group(['middleware' => 'auth:admin'], function () {

    //Префикс для работы с админкой
    Route::prefix('/api/admin')->group(function () {
        //Страницы
        Route::resource('/pages', PagesController::class);
        //Шаблоны
        Route::resource('/templates', TemplatesController::class);
        //Категории
        Route::resource('/categories', CategoriesController::class);
        //Теги страниц
        Route::resource('/tags', TagsController::class);
        //Категории tv параметров
        Route::resource('/tv_param_categories', TvParamCategoriesController::class);
        //TV параметры
        Route::resource('/tv_params', TvParamController::class);
        //Добавление файлов из редактора
        Route::post('/files/{filePath}', [FilesController::class, 'fileProcessing']);
    });
});

