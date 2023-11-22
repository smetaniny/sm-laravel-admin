<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class MenuItem extends Model
{
    use HasFactory, NodeTrait;

    protected $table = 'menu_items';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'icon',
        'order',
        'parent_id',
    ];
}
