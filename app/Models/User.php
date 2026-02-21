<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * توليد username تلقائياً من الاسم أو البريد إذا تُرك فارغاً.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (blank($user->username)) {
                $user->username = static::generateUniqueUsername($user);
            }
        });
    }

    /**
     * إنشاء username فريد من الاسم أو البريد.
     */
    public static function generateUniqueUsername(User $user): string
    {
        $base = null;
        if (filled($user->name)) {
            $base = Str::slug($user->name, '');
            $base = preg_replace('/[^a-zA-Z0-9_-]/', '', $base);
        }
        if (blank($base) && filled($user->email)) {
            $base = Str::before($user->email, '@');
            $base = preg_replace('/[^a-zA-Z0-9_-]/', '', $base);
        }
        if (blank($base)) {
            $base = 'user';
        }
        $base = Str::limit($base, 50);
        $username = $base;
        $counter = 1;
        while (static::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }
        return $username;
    }

    // -------------------------------------------------------------------------
    // Roles
    // -------------------------------------------------------------------------
    public const ROLE_ADMIN = 'admin';
    public const ROLE_USER = 'user';
    public const ROLE_TESTER = 'tester';

    public const ROLES = [
        self::ROLE_ADMIN  => 'مدير',
        self::ROLE_USER   => 'مستخدم',
        self::ROLE_TESTER => 'مختبر',
    ];

    // -------------------------------------------------------------------------
    // Statuses
    // -------------------------------------------------------------------------
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_BLOCKED = 'blocked';

    public const STATUSES = [
        self::STATUS_ACTIVE   => 'نشط',
        self::STATUS_INACTIVE => 'غير نشط',
        self::STATUS_BLOCKED  => 'محظور',
    ];

    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'role',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'deleted_at'       => 'datetime',
            'password'         => 'hashed',
        ];
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', self::STATUS_BLOCKED);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (blank($term)) {
            return $query;
        }
        $like = '%' . $term . '%';
        return $query->where(function ($q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('username', 'like', $like)
                ->orWhere('phone', 'like', $like);
        });
    }

    // -------------------------------------------------------------------------
    // Accessors / Helpers
    // -------------------------------------------------------------------------

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }
}
