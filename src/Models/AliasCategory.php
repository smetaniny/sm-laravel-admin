<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

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
