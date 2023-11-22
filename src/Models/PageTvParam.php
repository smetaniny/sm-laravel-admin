<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTvParam extends Model
{
    use HasFactory;

    protected $table = 'pages_tv_params';

    protected $fillable = [
        'page_id',
        'tv_param_id',
        'value'
    ];
}
