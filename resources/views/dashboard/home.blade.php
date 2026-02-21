@extends('layouts.dashboard.app')

@section('title', 'الرئيسية')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h5 page-title">لوحة التحكم</h2>
                    <p class="text-muted mb-0 small">نظرة شاملة على إحصائيات المنصة</p>
                </div>
            </div>

            {{-- المستخدمون --}}
            <h5 class="mb-2 mt-4">المستخدمون</h5>
            <div class="row">
                <x-stat-card title="إجمالي المستخدمين" :value="$stats['users']['total']" icon="fe-users" color="primary" :href="route('dashboard.users.index')" />
                <x-stat-card title="نشط" :value="$stats['users']['active']" icon="fe-check-circle" color="success" :href="route('dashboard.users.index', ['status' => 'active'])" />
                <x-stat-card title="غير نشط" :value="$stats['users']['inactive']" icon="fe-pause-circle" color="warning" :href="route('dashboard.users.index', ['status' => 'inactive'])" />
                <x-stat-card title="محظور" :value="$stats['users']['blocked']" icon="fe-slash" color="danger" :href="route('dashboard.users.index', ['status' => 'blocked'])" />
            </div>

            {{-- الفئات --}}
            <h5 class="mb-2 mt-4">الفئات</h5>
            <div class="row">
                <x-stat-card title="إجمالي الفئات" :value="$stats['categories']['total']" icon="fe-folder" color="primary" :href="route('dashboard.categories.index')" />
                <x-stat-card title="نشط" :value="$stats['categories']['active']" icon="fe-check-circle" color="success" :href="route('dashboard.categories.index', ['is_active' => '1'])" />
                <x-stat-card title="مميز" :value="$stats['categories']['featured']" icon="fe-star" color="warning" :href="route('dashboard.categories.index', ['is_featured' => '1'])" />
            </div>

            {{-- المحتويات --}}
            <h5 class="mb-2 mt-4">المحتويات</h5>
            <div class="row">
                <x-stat-card title="إجمالي المحتويات" :value="$stats['contents']['total']" icon="fe-film" color="primary" :href="route('dashboard.contents.index')" />
                <x-stat-card title="قنوات" :value="$stats['contents']['channels']" icon="fe-tv" color="info" :href="route('dashboard.contents.index', ['type' => 'channel'])" />
                <x-stat-card title="أفلام" :value="$stats['contents']['movies']" icon="fe-video" color="success" :href="route('dashboard.contents.index', ['type' => 'movie'])" />
                <x-stat-card title="مسلسلات" :value="$stats['contents']['series']" icon="fe-layers" color="warning" :href="route('dashboard.contents.index', ['type' => 'series'])" />
                <x-stat-card title="بث مباشر" :value="$stats['contents']['live']" icon="fe-radio" color="danger" :href="route('dashboard.contents.index', ['type' => 'live'])" />
            </div>
            <div class="row">
                <x-stat-card title="محتويات نشطة" :value="$stats['contents']['active']" icon="fe-eye" color="success" :href="route('dashboard.contents.index')" />
                <x-stat-card title="محتويات مميزة" :value="$stats['contents']['featured']" icon="fe-star" color="warning" :href="route('dashboard.contents.index')" />
            </div>

            {{-- الحلقات، السلايدر، التواصل، الإشعارات --}}
            <h5 class="mb-2 mt-4">الحلقات والتواصل</h5>
            <div class="row">
                <x-stat-card title="إجمالي الحلقات" :value="$stats['episodes']['total']" icon="fe-layers" color="primary" :href="route('dashboard.episodes.index')" />
                <x-stat-card title="إجمالي السلايدر" :value="$stats['sliders']['total']" icon="fe-image" color="info" :href="route('dashboard.sliders.index')" />
                <x-stat-card title="سلايدر نشط" :value="$stats['sliders']['active']" icon="fe-check-circle" color="success" :href="route('dashboard.sliders.index', ['is_active' => '1'])" />
                <x-stat-card title="رسائل التواصل" :value="$stats['contact_messages']['total']" icon="fe-mail" color="primary" :href="route('dashboard.contact_messages.index')" />
                <x-stat-card title="رسائل جديدة" :value="$stats['contact_messages']['new']" icon="fe-bell" color="warning" :href="route('dashboard.contact_messages.index', ['status' => 'new'])" />
                <x-stat-card title="تم الرد" :value="$stats['contact_messages']['replied']" icon="fe-check-circle" color="success" :href="route('dashboard.contact_messages.index', ['status' => 'replied'])" />
                <x-stat-card title="إشعارات البث" :value="$stats['broadcasts']['total']" icon="fe-send" color="info" :href="route('dashboard.notifications.index')" />
            </div>

            {{-- رسم بياني: توزيع المحتوى حسب النوع --}}
            <div class="row mt-4">
                <div class="col-md-12 col-lg-6 mb-4">
                    <div class="card shadow border-0">
                        <div class="card-header">
                            <strong>توزيع المحتوى حسب النوع</strong>
                        </div>
                        <div class="card-body">
                            <canvas id="chartContentByType" height="220"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 mb-4">
                    <div class="card shadow border-0">
                        <div class="card-header">
                            <strong>آخر المستخدمين المسجلين</strong>
                            <a href="{{ route('dashboard.users.index') }}" class="btn btn-sm btn-outline-primary float-left">عرض الكل</a>
                        </div>
                        <div class="card-body p-0">
                            @forelse($recentUsers as $user)
                                <a href="{{ route('dashboard.users.show', $user) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span>{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </a>
                            @empty
                                <p class="text-muted text-center py-4 mb-0">لا يوجد مستخدمون بعد</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- آخر رسائل التواصل --}}
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow border-0">
                        <div class="card-header">
                            <strong>آخر رسائل التواصل</strong>
                            <a href="{{ route('dashboard.contact_messages.index') }}" class="btn btn-sm btn-outline-primary float-left">عرض الكل</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>الموضوع</th>
                                            <th>الحالة</th>
                                            <th>التاريخ</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentContactMessages as $msg)
                                            <tr>
                                                <td>{{ $msg->name }}</td>
                                                <td>{{ Str::limit($msg->subject, 40) }}</td>
                                                <td><span class="badge badge-{{ $msg->status === \App\Models\ContactMessage::STATUS_NEW ? 'warning' : 'success' }}">{{ \App\Models\ContactMessage::STATUSES[$msg->status] ?? $msg->status }}</span></td>
                                                <td>{{ $msg->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ route('dashboard.contact_messages.show', $msg) }}" class="btn btn-sm btn-outline-secondary">عرض</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">لا توجد رسائل بعد</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('chartContentByType');
    if (!ctx) return;
    var labels = @json($chartContentByType['labels']);
    var data = @json($chartContentByType['data']);
    var colors = ['#467fcf', '#5eba00', '#f59c1a', '#e74c3c'];
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom' },
            cutoutPercentage: 60
        }
    });
});
</script>
@endpush
@endsection
