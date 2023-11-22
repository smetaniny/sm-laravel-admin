<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use App\Http\Controllers\Controller;
use Smetaniny\SmLaravelAdmin\Models\Page;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        return view('smetaniny::admin', [
        ]);
    }
}
