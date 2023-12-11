<?php

namespace Smetaniny\SmLaravelAdmin\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Путь к корневому шаблону, используемому Inertia.
     *
     * @var string
     */
    protected $rootView = 'smLaravelAdmin::admin';

    /**
     * Версионирование ресурсов Inertia.
     *
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string|null
     */
    public function version(Request $request)
    {
        // Составление версии с учетом корневого шаблона.
        return sprintf('%s:%s', $this->rootView, parent::version($request));
    }

}
