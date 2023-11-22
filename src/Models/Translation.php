<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translation extends BaseModel
{
    use HasFactory;

    protected $table = 'translations';

    protected $fillable = [
        'page_id',
        'translations_title',
        'translations_language'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
