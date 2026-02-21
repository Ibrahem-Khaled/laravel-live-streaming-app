<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-gray-900">{{ config('app.name') }}</a>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-sm text-gray-700 hover:text-gray-900">الملف الشخصي</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-700 hover:text-gray-900">تسجيل الخروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">تسجيل الدخول</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">التسجيل</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>
