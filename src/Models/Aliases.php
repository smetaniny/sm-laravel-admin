<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;

class Aliases extends BaseModel
{
    use HasFactory, NodeTrait;

    protected $table = 'alias';

    protected $fillable = [
        'alias_url',
        'page_id',
    ];
}
