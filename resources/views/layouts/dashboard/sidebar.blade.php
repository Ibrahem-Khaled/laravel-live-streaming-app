<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
        <i class="fe fe-x"><span class="sr-only"></span></i>
    </a>
    <nav class="vertnav navbar navbar-light">
        <!-- nav bar -->
        <div class="w-100 mb-4 d-flex">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('dashboard') }}">
                <h2 class="h5">لوحة التحكم</h2>
            </a>
        </div>

        <p class="text-muted nav-heading mt-4 mb-1">
            <span>الإدارة</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fe fe-home fe-16"></i>
                    <span class="ml-3 item-text">الرئيسية</span>
                </a>
            </li>
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.users.index') }}">
                    <i class="fe fe-users fe-16"></i>
                    <span class="ml-3 item-text">المستخدمين</span>
                </a>
            </li>
        </ul>

        <p class="text-muted nav-heading mt-4 mb-1">
            <span>المحتوى</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.categories.index') }}">
                    <i class="fe fe-folder fe-16"></i>
                    <span class="ml-3 item-text">الفئات</span>
                </a>
            </li>
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.contents.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.contents.index') }}">
                    <i class="fe fe-film fe-16"></i>
                    <span class="ml-3 item-text">المحتويات</span>
                </a>
            </li>
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.episodes.*') || request()->routeIs('dashboard.contents.episodes.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.episodes.index') }}">
                    <i class="fe fe-layers fe-16"></i>
                    <span class="ml-3 item-text">الحلقات</span>
                </a>
            </li>
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.sliders.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.sliders.index') }}">
                    <i class="fe fe-image fe-16"></i>
                    <span class="ml-3 item-text">السلايدر</span>
                </a>
            </li>
        </ul>

        <p class="text-muted nav-heading mt-4 mb-1">
            <span>التواصل والدعم</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.contact_messages.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.contact_messages.index') }}">
                    <i class="fe fe-mail fe-16"></i>
                    <span class="ml-3 item-text">رسائل التواصل</span>
                </a>
            </li>
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.notifications.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.notifications.index') }}">
                    <i class="fe fe-bell fe-16"></i>
                    <span class="ml-3 item-text">الإشعارات</span>
                </a>
            </li>
        </ul>
        {{-- <p class="text-muted nav-heading mt-4 mb-1">
            <span>أمثلة</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item dropdown">
                <a href="#ui-elements" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <i class="fe fe-box fe-16"></i>
                    <span class="ml-3 item-text">UI elements</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100" id="ui-elements">
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./ui-color.html"><span class="ml-1 item-text">Colors</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./ui-typograpy.html"><span
                                class="ml-1 item-text">Typograpy</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./ui-icons.html"><span class="ml-1 item-text">Icons</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./ui-buttons.html"><span
                                class="ml-1 item-text">Buttons</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./ui-notification.html"><span
                                class="ml-1 item-text">Notifications</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./ui-modals.html"><span class="ml-1 item-text">Modals</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./ui-tabs-accordion.html"><span class="ml-1 item-text">Tabs &
                                Accordion</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pl-3" href="./ui-progress.html"><span
                                class="ml-1 item-text">Progress</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item w-100">
                <a class="nav-link" href="widgets.html">
                    <i class="fe fe-layers fe-16"></i>
                    <span class="ml-3 item-text">Widgets</span>
                    <span class="badge badge-pill badge-primary">New</span>
                </a>
            </li>
            <li class="nav-item w-100">
                <a class="nav-link" href="calendar.html">
                    <i class="fe fe-calendar fe-16"></i>
                    <span class="ml-3 item-text">Calendar</span>
                </a>
            </li>
        </ul> --}}

        <p class="text-muted nav-heading mt-4 mb-1">
            <span>النظام</span>
        </p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item w-100 {{ request()->routeIs('dashboard.settings.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.settings.index') }}">
                    <i class="fe fe-settings fe-16"></i>
                    <span class="ml-3 item-text">الإعدادات</span>
                </a>
            </li>
        </ul>

    </nav>
</aside>
