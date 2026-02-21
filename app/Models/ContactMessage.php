<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_READ = 'read';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_CLOSED = 'closed';

    public const STATUSES = [
        self::STATUS_NEW     => 'جديد',
        self::STATUS_READ    => 'مقروء',
        self::STATUS_REPLIED => 'تم الرد',
        self::STATUS_CLOSED  => 'مغلق',
    ];

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'user_id',
        'status',
        'admin_reply',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }
}
