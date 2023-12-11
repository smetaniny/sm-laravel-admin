<?php

namespace Smetaniny\SmLaravelAdmin\Http\Controllers\Pages;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Smetaniny\SmLaravelAdmin\Models\Page;

class HomeController
{
    public function index()
    {
        return Inertia::render('admin', [
            'csrf_token' => Session::token(),
            'success' => Session::get('success'),
            'canLogin' => (bool) auth()->guard('admin')->user(),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'pages' => Page::get()->toTree(),
        ]);
    }
}
