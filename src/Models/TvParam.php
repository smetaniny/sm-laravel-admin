<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TvParam extends BaseModel
{
    use HasFactory;

    protected $table = 'tv_params';

    protected $fillable = [
        'name',
        'caption',
        'description',
        'type',
        'elements',
        'default_text',
        'default_number',
        'tv_param_template_id',
        'tv_param_category_id',
        'category',
        'input_type',
        'setting',
        'possible_values',
        'visual_component',
        'order',
        'category_id',
        'template_id',
    ];
}
