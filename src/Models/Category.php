<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

class Category extends BaseModel
{
    use HasFactory, NodeTrait;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'nameorder',
        '_lft',
        '_rgt',
        'nameparent_id'
    ];
}
