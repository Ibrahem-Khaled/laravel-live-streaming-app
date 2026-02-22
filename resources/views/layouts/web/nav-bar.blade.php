<nav class="py-4 px-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="logo-eye"></div>
                <span class="text-xl font-bold gold-text" style="font-family:'Cairo',sans-serif;">البصيرة</span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm" style="color: var(--text-dim);">
                <a href="#" class="animated-link hover:text-white transition-colors">الرئيسية</a>
                <a href="#" class="animated-link hover:text-white transition-colors">أفلام</a>
                <a href="#" class="animated-link hover:text-white transition-colors">مسلسلات</a>
                <a href="#" class="animated-link hover:text-white transition-colors">وثائقيات</a>
                <a href="#" class="animated-link hover:text-white transition-colors">أنيمي</a>
            </div>
            <div class="flex items-center gap-3">
                @if (!auth()->check())
                <a href="{{ route('login') }}" class="text-sm px-5 py-2 rounded-full border"
                    style="border-color:rgba(201,168,76,0.3); color:var(--gold-light);">تسجيل الدخول</a>
                <a href="{{ route('register') }}" class="text-sm px-5 py-2 rounded-full font-bold"
                    style="background:var(--gold); color:var(--deep);">ابدأ مجاناً</a>
                @else
                <a href="{{ route('dashboard') }}" class="text-sm px-5 py-2 rounded-full font-bold"
                    style="background:var(--gold); color:var(--deep);">لوحة التحكم</a>
                @endif


            </div>
        </div>
    </nav>