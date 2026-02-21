<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (Content $content) {
            if (blank($content->slug) && filled($content->name)) {
                $base = Str::slug($content->name);
                $slug = $base;
                $c = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $c++;
                }
                $content->slug = $slug;
            }
        });
    }

    public const TYPE_CHANNEL = 'channel';
    public const TYPE_MOVIE = 'movie';
    public const TYPE_SERIES = 'series';
    public const TYPE_LIVE = 'live';

    public const TYPES = [
        self::TYPE_CHANNEL => 'قناة',
        self::TYPE_MOVIE   => 'فيلم',
        self::TYPE_SERIES  => 'مسلسل',
        self::TYPE_LIVE    => 'بث مباشر',
    ];

    public const QUALITY_HD = 'HD';
    public const QUALITY_FHD = 'FHD';
    public const QUALITY_4K = '4K';
    public const QUALITY_SD = 'SD';

    public const QUALITIES = [
        self::QUALITY_SD  => 'SD',
        self::QUALITY_HD  => 'HD',
        self::QUALITY_FHD => 'FHD',
        self::QUALITY_4K  => '4K',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'poster',
        'banner',
        'type',
        'stream_url',
        'category_id',
        'sort_order',
        'is_active',
        'is_featured',
        'views_count',
        'year',
        'duration',
        'rating',
        'quality',
        'language',
        'country',
        'meta_title',
        'meta_description',
        'extra',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
            'views_count' => 'integer',
            'duration'    => 'integer',
            'rating'      => 'float',
            'year'        => 'integer',
            'extra'       => 'array',
            'deleted_at'  => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // العلاقات
    // -------------------------------------------------------------------------

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function episodes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Episode::class)->orderBy('season_number')->orderBy('episode_number');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeChannels($query)
    {
        return $query->where('type', self::TYPE_CHANNEL);
    }

    public function scopeMovies($query)
    {
        return $query->where('type', self::TYPE_MOVIE);
    }

    public function scopeSeries($query)
    {
        return $query->where('type', self::TYPE_SERIES);
    }

    public function scopeLive($query)
    {
        return $query->where('type', self::TYPE_LIVE);
    }

    public function scopeInCategory($query, $categoryId)
    {
        if (is_array($categoryId)) {
            return $query->whereIn('category_id', $categoryId);
        }
        return $query->where('category_id', $categoryId);
    }

    public function scopeInCategoryOrDescendants($query, Category $category)
    {
        return $query->whereIn('category_id', $category->getDescendantIds());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeSearch($query, ?string $term)
    {
        if (blank($term)) {
            return $query;
        }
        $like = '%' . $term . '%';
        return $query->where(function ($q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('description', 'like', $like);
        });
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isChannel(): bool
    {
        return $this->type === self::TYPE_CHANNEL;
    }

    public function isMovie(): bool
    {
        return $this->type === self::TYPE_MOVIE;
    }

    public function isSeries(): bool
    {
        return $this->type === self::TYPE_SERIES;
    }

    public function isLive(): bool
    {
        return $this->type === self::TYPE_LIVE;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getQualityLabelAttribute(): ?string
    {
        return $this->quality ? (self::QUALITIES[$this->quality] ?? $this->quality) : null;
    }

    /** صورة العرض (بوستر أو لوجو) */
    public function getDisplayImageAttribute(): ?string
    {
        return $this->poster ?: $this->image;
    }

    /** عدد حلقات المسلسل */
    public function getEpisodesCountAttribute(): int
    {
        if (!$this->relationLoaded('episodes')) {
            return $this->episodes()->count();
        }
        return $this->episodes->count();
    }

    /** عدد المواسم (للمسلسل) */
    public function getSeasonsCountAttribute(): int
    {
        return (int) $this->episodes()->distinct('season_number')->count('season_number');
    }

    /** زيادة عداد المشاهدات */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
