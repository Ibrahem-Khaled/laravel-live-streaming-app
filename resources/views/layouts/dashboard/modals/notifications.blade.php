<div class="modal fade modal-notif modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="defaultModalLabel">الإشعارات</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush my-n3">
                    @forelse($headerNotifications ?? [] as $notification)
                        @php
                            $data = $notification->data ?? [];
                            $title = $data['title'] ?? 'إشعار';
                            $body = $data['body'] ?? '';
                            $url = $data['url'] ?? null;
                        @endphp
                        <a href="{{ $url ?: '#' }}" class="list-group-item list-group-item-action bg-transparent {{ $url ? '' : 'disabled' }}" @if($url) target="_blank" @endif>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="fe fe-bell fe-24"></span>
                                </div>
                                <div class="col">
                                    <small><strong>{{ $title }}</strong></small>
                                    @if($body)
                                        <div class="my-0 text-muted small">{{ Str::limit($body, 60) }}</div>
                                    @endif
                                    <small class="badge badge-pill badge-light text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item bg-transparent text-center text-muted py-4">
                            <span class="fe fe-inbox fe-32 d-block mb-2"></span>
                            لا توجد إشعارات جديدة
                        </div>
                    @endforelse
                </div>
            </div>
            @if(($unreadNotificationsCount ?? 0) > 0)
                <div class="modal-footer">
                    <form method="post" action="{{ route('notifications.markAllRead') }}" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-block">تعليم الكل كمقروء</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
