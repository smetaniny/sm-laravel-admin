<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @method static where(string $string, $alias)
 */
class AliasCategory extends BaseModel
{
    use HasFactory, NodeTrait;

    protected $table = 'alias_categories';

    protected $fillable = [
        'slug',
        '_lft',
        '_rgt',
        'parent_id',
        'category_id',
        'page_id',
    ];
}
