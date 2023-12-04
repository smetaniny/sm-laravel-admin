<?php

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

Route::middleware(['web'])->group(function () {
    // Точка входа в админку
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

    // API Роуты
    Route::prefix('/api/admin')->group(function () {
        //Проверка авторизации
        Route::post('/auth_verification', [AuthVerificationController::class, 'authVerification']);
        //Авторизация
        Route::post('/login', [LoginController::class, 'login']);
        // Выход из админки
        Route::post('/logoutAdmin', [LoginController::class, 'destroy'])->name('logoutAdmin');
        //Получение url
        Route::post('/urlGet', [UrlGetController::class, 'urlGet']);

        // Защищенные роуты, доступные только после авторизации
        Route::group(['middleware' => 'auth:admin'], function () {
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
});




