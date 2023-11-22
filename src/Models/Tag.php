<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends BaseModel
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'name',
        'description'
    ];

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'pages_tags', 'tag_id', 'page_id');
    }
}
