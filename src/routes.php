<?php
use Illuminate\Support\Facades\Route;
use Smetaniny\SmLaravelAdmin\Controllers\AdminController;

Route::prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
});
