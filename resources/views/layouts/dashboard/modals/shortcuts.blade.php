<div class="modal fade modal-shortcut modal-slide" tabindex="-1" role="dialog" aria-labelledby="shortcutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shortcutModalLabel">اختصارات</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5">
                <div class="row align-items-center">
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none text-body">
                            <div class="squircle bg-success justify-content-center">
                                <i class="fe fe-home fe-32 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1">الرئيسية</p>
                        </a>
                    </div>
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('dashboard.users.index') }}" class="text-decoration-none text-body">
                            <div class="squircle bg-primary justify-content-center">
                                <i class="fe fe-users fe-32 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1">المستخدمين</p>
                        </a>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('dashboard.categories.index') }}" class="text-decoration-none text-body">
                            <div class="squircle bg-primary justify-content-center">
                                <i class="fe fe-folder fe-32 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1">الفئات</p>
                        </a>
                    </div>
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('dashboard.contents.index') }}" class="text-decoration-none text-body">
                            <div class="squircle bg-primary justify-content-center">
                                <i class="fe fe-film fe-32 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1">المحتويات</p>
                        </a>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('dashboard.episodes.index') }}" class="text-decoration-none text-body">
                            <div class="squircle bg-primary justify-content-center">
                                <i class="fe fe-layers fe-32 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1">الحلقات</p>
                        </a>
                    </div>
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('dashboard.sliders.index') }}" class="text-decoration-none text-body">
                            <div class="squircle bg-primary justify-content-center">
                                <i class="fe fe-image fe-32 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1">السلايدر</p>
                        </a>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('dashboard.contact_messages.index') }}" class="text-decoration-none text-body">
                            <div class="squircle bg-primary justify-content-center">
                                <i class="fe fe-mail fe-32 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1">رسائل التواصل</p>
                        </a>
                    </div>
                    <div class="col-6 text-center mb-3">
                        <a href="{{ route('dashboard.notifications.index') }}" class="text-decoration-none text-body">
                            <div class="squircle bg-primary justify-content-center">
                                <i class="fe fe-bell fe-32 align-self-center text-white"></i>
                            </div>
                            <p class="mb-0 mt-1">الإشعارات</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
