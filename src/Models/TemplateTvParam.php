<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $id)
 * @method static updateOrCreate(array $array, array $array1, mixed $tvParam)
 */
class TemplateTvParam extends Model
{
    use HasFactory;

    protected $table = 'templates_tv_params';

    protected $fillable = [
        'template_id',
        'tv_param_id',
        'page_id',
        'value'
    ];
}
