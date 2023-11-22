<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvParamCategory extends BaseModel
{
    use HasFactory;

    protected $table = 'tv_param_categories';

    protected $fillable = [
        'name'
    ];
}
