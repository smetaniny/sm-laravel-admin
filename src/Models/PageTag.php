<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageTag extends BaseModel
{
    use HasFactory;

    protected $table = 'pages_tags';

    protected $fillable = [
        'page_id',
        'tag_id'
    ];
}
