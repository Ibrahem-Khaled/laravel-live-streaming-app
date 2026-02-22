@extends('layouts.dashboard.app')

@section('title', 'إعدادات الموقع')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">إعدادات الموقع</h5>
                        <form action="{{ route('dashboard.settings.clear-cache') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                <i class="fe fe-refresh-cw fe-12 mr-1"></i> مسح التخزين المؤقت
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-light rounded-pill mb-4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-2 active" data-toggle="tab" href="#general" role="tab" aria-selected="true">
                                    <i class="fe fe-settings fe-16 mr-2"></i> عام
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-2" data-toggle="tab" href="#legal" role="tab" aria-selected="false">
                                    <i class="fe fe-file-text fe-16 mr-2"></i> الصفحات القانونية
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-2" data-toggle="tab" href="#maintenance" role="tab" aria-selected="false">
                                    <i class="fe fe-tool fe-16 mr-2"></i> وضع الصيانة
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-2" data-toggle="tab" href="#seo" role="tab" aria-selected="false">
                                    <i class="fe fe-search fe-16 mr-2"></i> SEO والتواصل
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-2" data-toggle="tab" href="#custom" role="tab" aria-selected="false">
                                    <i class="fe fe-code fe-16 mr-2"></i> أكواد مخصصة
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content mt-4">
                            <!-- General Settings -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="group" value="general">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">اسم الموقع</label>
                                            <input type="text" name="site_name" class="form-control" value="{{ $settings->get('site_name', config('app.name')) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">بريد التواصل</label>
                                            <input type="email" name="contact_email" class="form-control" value="{{ $settings->get('contact_email') }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">شعار الموقع (Logo)</label>
                                            <input type="file" name="site_logo" class="form-control">
                                            @if($settings->get('site_logo'))
                                                <img src="{{ $settings->get('site_logo') }}" class="mt-2 rounded bg-dark p-2" height="50">
                                            @endif
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">أيقونة الموقع (Favicon)</label>
                                            <input type="file" name="site_favicon" class="form-control">
                                            @if($settings->get('site_favicon'))
                                                <img src="{{ $settings->get('site_favicon') }}" class="mt-2 rounded" height="32">
                                            @endif
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">حفظ التغييرات</button>
                                </form>
                            </div>

                            <!-- Legal Pages -->
                            <div class="tab-pane fade" id="legal" role="tabpanel">
                                <form action="{{ route('dashboard.settings.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group" value="legal">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">سياسة الخصوصية</label>
                                        <textarea name="privacy_policy" class="form-control" rows="10">{{ $settings->get('privacy_policy') }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">شروط الاستخدام</label>
                                        <textarea name="terms_of_service" class="form-control" rows="10">{{ $settings->get('terms_of_service') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">حفظ التغييرات</button>
                                </form>
                            </div>

                            <!-- Maintenance Mode -->
                            <div class="tab-pane fade" id="maintenance" role="tabpanel">
                                <form action="{{ route('dashboard.settings.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group" value="maintenance">
                                    <div class="mb-3 custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="maintenanceMode" name="maintenance_mode" value="1" {{ $settings->get('maintenance_mode') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="maintenanceMode">تفعيل وضع الصيانة</label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">رسالة الصيانة</label>
                                        <textarea name="maintenance_message" class="form-control" rows="3">{{ $settings->get('maintenance_message') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">عناوين IP المستثناة (مفصولة بفاصلة)</label>
                                        <input type="text" name="maintenance_ips" class="form-control" value="{{ $settings->get('maintenance_ips') }}" placeholder="127.0.0.1, 192.168.1.1">
                                    </div>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">حفظ التغييرات</button>
                                </form>
                            </div>

                            <!-- SEO & Social -->
                            <div class="tab-pane fade" id="seo" role="tabpanel">
                                <form action="{{ route('dashboard.settings.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group" value="seo">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">وصف الميتا (Meta Description)</label>
                                            <textarea name="meta_description" class="form-control" rows="3">{{ $settings->get('meta_description') }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">الكلمات الدلالية (Meta Keywords)</label>
                                            <textarea name="meta_keywords" class="form-control" rows="3">{{ $settings->get('meta_keywords') }}</textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">رابط فيسبوك</label>
                                            <input type="url" name="facebook_link" class="form-control" value="{{ $settings->get('facebook_link') }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">رابط تويتر</label>
                                            <input type="url" name="twitter_link" class="form-control" value="{{ $settings->get('twitter_link') }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">رابط انستجرام</label>
                                            <input type="url" name="instagram_link" class="form-control" value="{{ $settings->get('instagram_link') }}">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">حفظ التغييرات</button>
                                </form>
                            </div>

                            <!-- Custom Scripts -->
                            <div class="tab-pane fade" id="custom" role="tabpanel">
                                <form action="{{ route('dashboard.settings.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group" value="custom">
                                    <div class="mb-3">
                                        <label class="form-label">أكواد الهيدر (Header Scripts - مثل Google Analytics)</label>
                                        <textarea name="header_scripts" class="form-control code-editor" rows="8" dir="ltr">{{ $settings->get('header_scripts') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">أكواد الفوتر (Footer Scripts)</label>
                                        <textarea name="footer_scripts" class="form-control code-editor" rows="8" dir="ltr">{{ $settings->get('footer_scripts') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill">حفظ التغييرات</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .nav-pills .nav-link {
        color: #adb5bd;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    .nav-pills .nav-link.active {
        background-color: #343a40 !important;
        color: #fff !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #495057;
    }
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }
    .card {
        border-radius: 1rem;
        background-color: #1b1e21;
    }
    .code-editor {
        font-family: 'Fira Code', 'Monaco', 'Consolas', monospace;
        background: #121517 !important;
        color: #f8fafc !important;
        border: 1px solid #343a40 !important;
    }
    .code-editor:focus {
        background: #000 !important;
        color: #fff !important;
    }
    .bg-light {
        background-color: #24282d !important;
    }
</style>
@endpush
