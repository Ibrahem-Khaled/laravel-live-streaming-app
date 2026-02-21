<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class NotificationBroadcast extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'url',
        'audience_type',
        'recipients_count',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * قائمة المستلمين مع حالة القراءة (من جدول notifications).
     */
    public function getRecipientsWithReadStatus(): \Illuminate\Support\Collection
    {
        $type = \App\Notifications\GeneralNotification::class;

        $rows = DB::table('notifications')
            ->where('type', $type)
            ->whereNotNull('data')
            ->where('data->broadcast_id', $this->id)
            ->get(['id', 'notifiable_id', 'notifiable_type', 'read_at', 'created_at']);

        $userIds = $rows->pluck('notifiable_id')->unique()->filter()->values()->all();
        $users = User::query()->whereIn('id', $userIds)->get()->keyBy('id');

        return $rows->map(function ($row) use ($users) {
            $user = $users->get($row->notifiable_id);
            return (object) [
                'user_id'    => $row->notifiable_id,
                'user'       => $user,
                'read_at'    => $row->read_at ? \Carbon\Carbon::parse($row->read_at) : null,
                'created_at' => \Carbon\Carbon::parse($row->created_at),
            ];
        });
    }

    /**
     * حذف الإشعار المرسل وحذف نسخ الإشعار من صناديق المستلمين.
     */
    public function deleteWithRelatedNotifications(): void
    {
        $type = \App\Notifications\GeneralNotification::class;

        DB::table('notifications')
            ->where('type', $type)
            ->where('data->broadcast_id', $this->id)
            ->delete();

        $this->delete();
    }
}
