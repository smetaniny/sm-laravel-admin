<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @method static get()
 * @method static find(int $id)
 * @method static findOrFail(int $id)
 * @method static where(string $string, mixed $alias)
 * @method static max(string $string)
 */
class Page extends BaseModel
{
    use HasFactory, SoftDeletes, NodeTrait;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'menutitle',
        'alias',
        'menuindex',
        'language',
        'content_js',
        'content',
        'description',
        'meta_keywords',
        'is_published',
        'is_visible_url',
        'is_open',
        'published_at',
        'unpublished_at',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'css',
        'js',
        'robots',
        'sitemap_priority',
        'sitemap_frequency',
        'parent_id',
        'comments_count',
        'views_count',
        'likes_count',
        'shares_count',
        'category_id ',
        '_lft',
        '_rgt',
        'template_id',
        'author_id',
        'updated_by_id',
    ];

    protected $hidden = ['tags', 'tags_new'];

    protected $with = [
        'withAuthor',
        'withUpdater',
        'withCategories',
        'withAlias',
        'withAliasCategories',
        'withMenu',
        'withTranslations',
        'withPagesTags',
        'withTemplates',
        'withTemplatesTv',
        'withTvParams',
    ];

    public function withAuthor(): BelongsTo
    {
        return $this->belongsTo(UserAdmin::class, 'author_id');
    }

    public function withUpdater(): BelongsTo
    {
        return $this->belongsTo(UserAdmin::class, 'updated_by_id');
    }

    public function withCategories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function withTranslations(): BelongsTo
    {
        return $this->belongsTo(Translation::class, 'page_id');

    }

    public function withPagesTags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'pages_tags', 'page_id', 'tag_id');
    }

    public function withAliasCategories(): HasManyThrough
    {
        return $this->hasManyThrough(Aliases::class, AliasCategory::class, 'category_id', 'id', 'id', 'category_id');
    }

    public function withAlias(): HasOne
    {
        return $this->hasOne(Aliases::class, 'page_id');
    }

    public function withMenu(): HasMany
    {
        return $this->hasMany(Menu::class, 'page_id');
    }

    public function withTemplates(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function withTemplatesTv()
    {
        return $this->belongsToMany(Template::class, 'templates_tv_params', 'page_id', 'template_id')
            ->withPivot('value');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function withTvParams()
    {
        return $this->belongsToMany(TvParam::class, 'templates_tv_params', 'page_id', 'tv_param_id')->withPivot('value', 'page_id');
    }

    public function setMenuindexAttribute($value)
    {
        // Проверяем, изменился ли индекс страницы
        if ($this->menuindex !== $value) {
            // Определяем текущий индекс страницы
            $currentIndex = $this->menuindex;

            // Устанавливаем новый индекс страницы
            $this->attributes['menuindex'] = $value;
            $this->update();

            // Если новый индекс меньше текущего, смещаем индексы страниц, которые находятся между новым и текущим индексом
            if ($value < $currentIndex) {
                Page::where('menuindex', '>=', $value)
                    ->where('menuindex', '<', $currentIndex)
                    ->decrement('menuindex');
            }

            // Если новый индекс больше текущего, смещаем индексы страниц, которые находятся между текущим и новым индексом
            if ($value > $currentIndex) {
                Page::whereBetween('menuindex', [$currentIndex + 1, $value])
                    ->increment('menuindex');
            }

        } else {
            // Индекс не изменился, просто устанавливаем значение
            $this->attributes['menuindex'] = $value;
        }
    }
}
