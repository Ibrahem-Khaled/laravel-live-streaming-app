<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Episode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content_id',
        'season_number',
        'episode_number',
        'title',
        'description',
        'image',
        'stream_url',
        'duration',
        'sort_order',
        'aired_at',
    ];

    protected function casts(): array
    {
        return [
            'season_number'  => 'integer',
            'episode_number' => 'integer',
            'duration'       => 'integer',
            'sort_order'     => 'integer',
            'aired_at'       => 'date',
            'deleted_at'     => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // العلاقات
    // -------------------------------------------------------------------------

    public function content(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeSeason($query, int $season)
    {
        return $query->where('season_number', $season);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('season_number')->orderBy('episode_number');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** تسمية الحلقة (مثلاً S01E05) */
    public function getSeasonEpisodeLabelAttribute(): string
    {
        return sprintf('S%02dE%02d', $this->season_number, $this->episode_number);
    }

    /** المدة بصيغة قابلة للقراءة */
    public function getDurationFormattedAttribute(): ?string
    {
        if (!$this->duration) {
            return null;
        }
        $m = (int) floor($this->duration / 60);
        $s = $this->duration % 60;
        if ($m >= 60) {
            $h = (int) floor($m / 60);
            $m = $m % 60;
            return sprintf('%d:%02d:%02d', $h, $m, $s);
        }
        return sprintf('%d:%02d', $m, $s);
    }

    /** عنوان العرض (مع رقم الحلقة) */
    public function getDisplayTitleAttribute(): string
    {
        $label = $this->season_episode_label;
        return $this->title ? "{$label} - {$this->title}" : $label;
    }
}
