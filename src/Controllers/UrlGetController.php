<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use Illuminate\Http\Request;

class UrlGetController extends BaseAdminController
{
    public function urlGet(Request $request)
    {
        return PageHelper::generatePageUrl(basename($request->input("alias")));
    }

}
