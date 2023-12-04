<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @method static where(string $string, $id)
 */
class Menu extends BaseModel
{
    use HasFactory, NodeTrait;

    protected $table = 'menu';

    protected $fillable = [
        'id',
        'parent_id',
        'title',
        'slug',
        'order',
        'is_enabled',
        '_lft',
        '_rgt',
        'page_id',
    ];
}
