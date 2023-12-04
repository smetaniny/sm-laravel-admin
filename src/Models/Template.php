<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * @method static find(int $id)
 * @method static findOrFail(int $id)
 */
class Template extends BaseModel
{
    use HasFactory;

    protected $table = 'templates';

    protected $fillable = [
        'name',
        'alias',
        'description',
        'tv_param_category_id',
        'tv_param_template_id',
        'header_code',
        'footer_code',
    ];

    protected $with = [
        'withTvParams',
        'withTvParamsTemplate'
    ];


    public function withTvParams()
    {
        return $this->hasMany(TvParam::class, 'tv_param_template_id');
    }

    public function withTvParamsTemplate()
    {
        return $this->belongsToMany(TvParam::class, 'templates_tv_params', 'template_id', 'tv_param_id')->withPivot('value', 'page_id');
    }
}
