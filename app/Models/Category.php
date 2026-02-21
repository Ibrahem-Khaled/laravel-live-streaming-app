<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (blank($category->slug) && filled($category->name)) {
                $base = Str::slug($category->name);
                $slug = $base;
                $c = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $c++;
                }
                $category->slug = $slug;
            }
        });
    }

    public const CONTENT_TYPE_CHANNEL = 'channel';
    public const CONTENT_TYPE_MOVIE = 'movie';
    public const CONTENT_TYPE_SERIES = 'series';
    public const CONTENT_TYPE_LIVE = 'live';

    public const CONTENT_TYPES = [
        self::CONTENT_TYPE_CHANNEL => 'قناة',
        self::CONTENT_TYPE_MOVIE   => 'فيلم',
        self::CONTENT_TYPE_SERIES  => 'مسلسل',
        self::CONTENT_TYPE_LIVE    => 'بث مباشر',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'parent_id',
        'content_type',
        'sort_order',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
            'deleted_at'  => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // العلاقات
    // -------------------------------------------------------------------------

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /** جميع الأحفاد بشكل متداخل (أبناء الأبناء...) */
    public function descendants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->with('descendants');
    }

    public function contents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Content::class)->orderBy('sort_order');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByContentType($query, string $type)
    {
        return $query->where('content_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /** مستوى العمق (0 = جذر) */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $parent = $this->parent;
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        return $depth;
    }

    /** جميع معرفات الفئات الفرعية تحت هذه الفئة (بما فيها نفسها) */
    public function getDescendantIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getDescendantIds());
        }
        return $ids;
    }
}
