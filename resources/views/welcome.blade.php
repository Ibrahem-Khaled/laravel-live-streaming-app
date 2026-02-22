<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>البصيرة — عالمك السينمائي</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700;900&family=Cairo:wght@300;400;700;900&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- meta data and app icon and desc and seo -->
    <meta name="description" content="البصيرة — منصتك المتكاملة لاستكشاف عالم السينما، الأفلام، والمسلسلات بأحدث التقنيات والمراجعات." />
    <meta name="keywords" content="سينما, أفلام, مسلسلات, مراجعات, البصيرة, ترفيه" />
    <link rel="icon" type="image/png" href="{{ asset('dashboard/assets/images/logo.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('dashboard/assets/images/logo.png') }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="البصيرة — عالمك السينمائي" />
    <meta property="og:description" content="البصيرة — منصتك المتكاملة لاستكشاف عالم السينما، الأفلام، والمسلسلات بأحدث التقنيات والمراجعات." />
    <meta property="og:image" content="{{ asset('dashboard/assets/images/logo.png') }}" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ url()->current() }}" />
    <meta name="twitter:title" content="البصيرة — عالمك السينمائي" />
    <meta name="twitter:description" content="البصيرة — منصتك المتكاملة لاستكشاف عالم السينما، الأفلام، والمسلسلات بأحدث التقنيات والمراجعات." />
    <meta name="twitter:image" content="{{ asset('dashboard/assets/images/logo.png') }}" />

    <style>
        :root {
            --gold: #c9a84c;
            --gold-light: #e8c96a;
            --deep: #08080f;
            --surface: #0f0f1a;
            --card: #141422;
            --muted: #2a2a3e;
            --text-dim: #8888aa;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--deep);
            font-family: 'Tajawal', sans-serif;
            color: #e8e8f0;
            overflow-x: hidden;
            cursor: none;
        }

        /* Custom cursor */
        .cursor {
            width: 10px;
            height: 10px;
            background: var(--gold);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 99999;
            top: 0;
            left: 0;
            will-change: transform;
            box-shadow: 0 0 8px rgba(201, 168, 76, 0.8);
            transition: width 0.2s, height 0.2s, background 0.2s;
        }

        .cursor.hovering {
            width: 6px;
            height: 6px;
            background: #fff;
        }

        .cursor-trail {
            width: 36px;
            height: 36px;
            border: 1.5px solid var(--gold);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 99998;
            top: 0;
            left: 0;
            will-change: transform;
            opacity: 0.5;
            transition: width 0.2s, height 0.2s, opacity 0.2s, border-color 0.2s;
        }

        .cursor-trail.hovering {
            width: 44px;
            height: 44px;
            border-color: rgba(201, 168, 76, 0.5);
            opacity: 0.3;
        }

        /* Noise overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            opacity: 0.3;
            pointer-events: none;
            z-index: 0;
        }

        /* Hero gradient background */
        .hero-bg {
            background:
                radial-gradient(ellipse 80% 60% at 50% -10%, rgba(201, 168, 76, 0.18) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 80% 60%, rgba(201, 168, 76, 0.08) 0%, transparent 60%),
                var(--deep);
        }

        /* Animated stars */
        .stars {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            animation: twinkle var(--dur, 3s) infinite var(--delay, 0s);
            opacity: 0;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0;
            }

            50% {
                opacity: var(--max-op, 0.6);
            }
        }

        /* Gold gradient text */
        .gold-text {
            background: linear-gradient(135deg, #c9a84c 0%, #f0d87a 45%, #c9a84c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Nav */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            backdrop-filter: blur(20px);
            background: rgba(8, 8, 15, 0.7);
            border-bottom: 1px solid rgba(201, 168, 76, 0.12);
        }

        /* Logo mark */
        .logo-eye {
            width: 36px;
            height: 36px;
            border: 2px solid var(--gold);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            position: relative;
            animation: pulse-eye 4s ease-in-out infinite;
        }

        .logo-eye::after {
            content: '';
            width: 12px;
            height: 12px;
            background: var(--gold);
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes pulse-eye {

            0%,
            100% {
                transform: scaleY(1);
            }

            50% {
                transform: scaleY(0.7);
            }
        }

        /* Hero title */
        .hero-title {
            font-family: 'Cairo', sans-serif;
            font-weight: 900;
            font-size: clamp(4rem, 12vw, 9rem);
            line-height: 1;
            letter-spacing: -2px;
        }

        /* Horizontal scroll section */
        .h-scroll {
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-snap-type: x mandatory;
        }

        .h-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Movie card */
        .movie-card {
            min-width: 220px;
            scroll-snap-align: start;
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            cursor: none;
            transition: transform 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .movie-card:hover {
            transform: scale(1.05) translateY(-8px);
        }

        .movie-card img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
            transition: filter 0.4s;
        }

        .movie-card:hover img {
            filter: brightness(0.5);
        }

        .movie-card .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(8, 8, 15, 0.95) 0%, transparent 50%);
            transition: background 0.4s;
        }

        .movie-card:hover .overlay {
            background: linear-gradient(to top, rgba(8, 8, 15, 0.98) 0%, rgba(8, 8, 15, 0.6) 100%);
        }

        .movie-card .info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
        }

        .movie-card .extra {
            opacity: 0;
            transform: translateY(12px);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .movie-card:hover .extra {
            opacity: 1;
            transform: translateY(0);
        }

        /* Genre badge */
        .genre-badge {
            background: rgba(201, 168, 76, 0.15);
            border: 1px solid rgba(201, 168, 76, 0.3);
            color: var(--gold-light);
            font-size: 0.7rem;
            padding: 2px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        /* Big feature card */
        .feature-card {
            background: var(--card);
            border: 1px solid rgba(201, 168, 76, 0.1);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            transition: border-color 0.3s, transform 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .feature-card:hover {
            border-color: rgba(201, 168, 76, 0.35);
            transform: translateY(-6px);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top right, rgba(201, 168, 76, 0.06) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Stat counter */
        .stat-num {
            font-family: 'Cairo', sans-serif;
            font-weight: 900;
            font-size: 3.5rem;
            line-height: 1;
        }

        /* Play button */
        .play-btn {
            width: 64px;
            height: 64px;
            border: 2px solid var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            position: relative;
        }

        .play-btn::before {
            content: '';
            position: absolute;
            inset: -8px;
            border: 1px solid rgba(201, 168, 76, 0.2);
            border-radius: 50%;
            animation: ripple 2s ease-out infinite;
        }

        @keyframes ripple {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            100% {
                opacity: 0;
                transform: scale(1.5);
            }
        }

        .play-btn:hover {
            background: var(--gold);
            box-shadow: 0 0 40px rgba(201, 168, 76, 0.4);
        }

        .play-btn:hover svg {
            fill: var(--deep);
        }

        /* Section reveal animation */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Divider line */
        .gold-line {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold), transparent);
        }

        /* Pricing card */
        .price-card {
            background: var(--card);
            border: 1px solid rgba(201, 168, 76, 0.1);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
        }

        .price-card.featured {
            border-color: var(--gold);
            background: linear-gradient(145deg, rgba(201, 168, 76, 0.1), var(--card));
        }

        .price-card.featured::after {
            content: 'الأكثر شعبية';
            position: absolute;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gold);
            color: var(--deep);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 16px;
            border-radius: 20px;
        }

        .price-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
        }

        /* Footer */
        footer {
            background: linear-gradient(to top, #000 0%, var(--deep) 100%);
            border-top: 1px solid rgba(201, 168, 76, 0.1);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--deep);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 2px;
        }

        /* Animated underline */
        .animated-link {
            position: relative;
        }

        .animated-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--gold);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .animated-link:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Floating badge on hero */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .float {
            animation: float 4s ease-in-out infinite;
        }

        /* Category pills */
        .cat-pill {
            border: 1px solid rgba(201, 168, 76, 0.25);
            border-radius: 40px;
            padding: 8px 24px;
            color: var(--text-dim);
            transition: all 0.3s;
            cursor: none;
            white-space: nowrap;
        }

        .cat-pill:hover,
        .cat-pill.active {
            background: rgba(201, 168, 76, 0.15);
            border-color: var(--gold);
            color: var(--gold-light);
        }

        /* Marquee */
        .marquee-track {
            display: flex;
            gap: 2rem;
            animation: marquee 20s linear infinite;
            width: max-content;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        /* Input */
        input[type="email"] {
            background: var(--muted);
            border: 1px solid rgba(201, 168, 76, 0.2);
            color: #e8e8f0;
            border-radius: 40px;
            padding: 14px 24px;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus {
            border-color: var(--gold);
        }

        input[type="email"]::placeholder {
            color: var(--text-dim);
        }
    </style>
</head>

<body>

    <!-- Cursor -->
    <div class="cursor" id="cursor"></div>
    <div class="cursor-trail" id="cursorTrail"></div>

    <!-- ===== NAVBAR ===== -->
    @include('layouts.web.nav-bar')

    <!-- ===== HERO ===== -->
    <section class="hero-bg relative min-h-screen flex items-center pt-20 overflow-hidden">

        <!-- Stars -->
        <div class="stars" id="stars"></div>

        <!-- Decorative circles -->
        <div class="absolute top-20 left-[-100px] w-96 h-96 rounded-full opacity-10"
            style="border: 1px solid var(--gold); filter: blur(2px);"></div>
        <div class="absolute bottom-10 right-[-80px] w-72 h-72 rounded-full opacity-5"
            style="border: 1px solid var(--gold);"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Left: Text -->
                <div>
                    <div class="genre-badge mb-6">🎬 منصة المحتوى العربية الأولى</div>
                    <h1 class="hero-title mb-6">
                        <span class="text-white">كل</span><br />
                        <span class="gold-text">قصة</span><br />
                        <span class="text-white">تستحق</span><br />
                        <span style="color: var(--text-dim); font-size:0.6em;">أن تُرى</span>
                    </h1>
                    <p class="text-lg mb-10 leading-relaxed" style="color:var(--text-dim); max-width:440px;">
                        آلاف الأفلام والمسلسلات والوثائقيات والأنمي من كل أنحاء العالم — بجودة 4K وصوت مدهش، كل شيء في
                        مكان واحد.
                    </p>
                    <div class="flex items-center gap-4 flex-wrap">
                        <button class="px-8 py-4 rounded-full font-bold text-lg transition-all hover:shadow-lg"
                            style="background:var(--gold);color:var(--deep); hover:box-shadow:0 0 40px rgba(201,168,76,0.5);">
                            ابدأ مجاناً — 30 يوم
                        </button>
                        <button class="flex items-center gap-3 group">
                            <div class="play-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </div>
                            <span style="color:var(--text-dim);" class="group-hover:text-white transition-colors">شاهد
                                الإعلان</span>
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="flex gap-10 mt-14">
                        <div>
                            <div class="stat-num gold-text">50K+</div>
                            <div class="text-sm mt-1" style="color:var(--text-dim);">فيلم ومسلسل</div>
                        </div>
                        <div class="w-px" style="background:rgba(201,168,76,0.2);"></div>
                        <div>
                            <div class="stat-num gold-text">4K</div>
                            <div class="text-sm mt-1" style="color:var(--text-dim);">جودة فائقة</div>
                        </div>
                        <div class="w-px" style="background:rgba(201,168,76,0.2);"></div>
                        <div>
                            <div class="stat-num gold-text">12M</div>
                            <div class="text-sm mt-1" style="color:var(--text-dim);">مشترك نشط</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Movie grid -->
                <div class="relative hidden lg:block">
                    <!-- Floating badge -->
                    <div
                        class="float absolute -top-4 -right-4 z-20 bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg">
                        🔴 الآن على الهواء
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Card 1 -->
                        <div class="movie-card col-span-1 row-span-2" style="border-radius:16px;">
                            <img src="https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400&h=560&fit=crop"
                                alt="فيلم" />
                            <div class="overlay"></div>
                            <div class="info">
                                <span class="genre-badge">أكشن</span>
                                <h4 class="font-bold mt-2">أبطال الظلام</h4>
                                <p class="text-xs mt-1" style="color:var(--text-dim);">2024 • ١h ٤٨m</p>
                                <div class="extra mt-3">
                                    <div class="flex items-center gap-1 text-yellow-400 text-xs">⭐ 8.7 / 10</div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="movie-card">
                            <img src="https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?w=400&h=250&fit=crop"
                                alt="مسلسل" />
                            <div class="overlay"></div>
                            <div class="info">
                                <span class="genre-badge">دراما</span>
                                <h4 class="font-bold mt-1 text-sm">مدينة الأسرار</h4>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="movie-card">
                            <img src="https://images.unsplash.com/photo-1533488765986-dfa2a9939acd?w=400&h=250&fit=crop"
                                alt="وثائقي" />
                            <div class="overlay"></div>
                            <div class="info">
                                <span class="genre-badge">وثائقي</span>
                                <h4 class="font-bold mt-1 text-sm">عمق المحيط</h4>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== MARQUEE ===== -->
    <div class="py-6 overflow-hidden"
        style="border-top:1px solid rgba(201,168,76,0.08); border-bottom:1px solid rgba(201,168,76,0.08);">
        <div class="marquee-track" style="color:var(--text-dim); font-size:0.85rem;">
            <span>🎬 أفلام عالمية</span><span class="gold-text mx-4">✦</span>
            <span>📺 مسلسلات عربية وأجنبية</span><span class="gold-text mx-4">✦</span>
            <span>🎌 أنيمي ياباني</span><span class="gold-text mx-4">✦</span>
            <span>🌍 وثائقيات عالمية</span><span class="gold-text mx-4">✦</span>
            <span>🏆 أفلام حائزة على أوسكار</span><span class="gold-text mx-4">✦</span>
            <span>🎭 كوميديا وكلاسيكيات</span><span class="gold-text mx-4">✦</span>
            <span>🎬 أفلام عالمية</span><span class="gold-text mx-4">✦</span>
            <span>📺 مسلسلات عربية وأجنبية</span><span class="gold-text mx-4">✦</span>
            <span>🎌 أنيمي ياباني</span><span class="gold-text mx-4">✦</span>
            <span>🌍 وثائقيات عالمية</span><span class="gold-text mx-4">✦</span>
            <span>🏆 أفلام حائزة على أوسكار</span><span class="gold-text mx-4">✦</span>
            <span>🎭 كوميديا وكلاسيكيات</span><span class="gold-text mx-4">✦</span>
        </div>
    </div>

    <!-- ===== CATEGORIES ===== -->
    <section class="py-24 px-6 reveal" id="cats">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold mb-3" style="font-family:'Cairo',sans-serif;">تصفّح حسب <span
                        class="gold-text">الفئة</span></h2>
                <p style="color:var(--text-dim);">من الأكشن المثير إلى الرومانسية الهادئة</p>
            </div>

            <div class="flex gap-3 flex-wrap justify-center mb-16">
                <div class="cat-pill active">الكل</div>
                <div class="cat-pill">أفلام</div>
                <div class="cat-pill">مسلسلات</div>
                <div class="cat-pill">أنيمي</div>
                <div class="cat-pill">وثائقيات</div>
                <div class="cat-pill">رعب</div>
                <div class="cat-pill">كوميديا</div>
                <div class="cat-pill">رومانسي</div>
                <div class="cat-pill">خيال علمي</div>
            </div>

            <!-- Feature cards grid -->
            <div class="grid md:grid-cols-3 gap-6">

                <div class="feature-card md:col-span-2 p-0">
                    <div class="relative h-72 overflow-hidden rounded-t-2xl">
                        <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&h=400&fit=crop"
                            class="w-full h-full object-cover" />
                        <div class="absolute inset-0"
                            style="background:linear-gradient(to left, transparent 30%, rgba(8,8,15,0.9))"></div>
                        <div class="absolute inset-0 flex items-center p-8">
                            <div>
                                <div class="genre-badge mb-3">🏆 الأكثر مشاهدة</div>
                                <h3 class="text-3xl font-bold mb-2" style="font-family:'Cairo',sans-serif;">محارب
                                    الأبدية</h3>
                                <p class="text-sm mb-4" style="color:var(--text-dim);">أكشن | خيال علمي | ٢h ١٦m</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-yellow-400">⭐ 9.1</span>
                                    <span style="color:var(--text-dim);">• 2024</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-between">
                        <p class="text-sm" style="color:var(--text-dim);">ملحمة خيالية تأخذك في رحلة عبر الزمن
                            والأكوان...</p>
                        <button class="px-6 py-2 rounded-full font-bold text-sm"
                            style="background:var(--gold); color:var(--deep);">شاهد الآن</button>
                    </div>
                </div>

                <div class="grid gap-6">
                    <div class="feature-card p-5 flex gap-4 items-start">
                        <img src="https://images.unsplash.com/photo-1620641788421-7a1c342ea42e?w=120&h=160&fit=crop"
                            class="w-20 h-28 object-cover rounded-lg flex-shrink-0" />
                        <div>
                            <span class="genre-badge text-xs">دراما</span>
                            <h4 class="font-bold mt-2 mb-1">الصمت الأزرق</h4>
                            <p class="text-xs" style="color:var(--text-dim);">قصة إنسانية مؤثرة عن الفقد والأمل</p>
                            <div class="flex items-center gap-2 mt-3">
                                <span class="text-yellow-400 text-xs">⭐ 8.4</span>
                            </div>
                        </div>
                    </div>
                    <div class="feature-card p-5 flex gap-4 items-start">
                        <img src="https://images.unsplash.com/photo-1579762715118-a6f1d4b934f1?w=120&h=160&fit=crop"
                            class="w-20 h-28 object-cover rounded-lg flex-shrink-0" />
                        <div>
                            <span class="genre-badge text-xs">🎌 أنيمي</span>
                            <h4 class="font-bold mt-2 mb-1">روح النار</h4>
                            <p class="text-xs" style="color:var(--text-dim);">مغامرة خيالية من عالم الكاتكانا</p>
                            <div class="flex items-center gap-2 mt-3">
                                <span class="text-yellow-400 text-xs">⭐ 9.3</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== TRENDING HORIZONTAL SCROLL ===== -->
    <section class="py-16 reveal">
        <div class="max-w-7xl mx-auto px-6 mb-8 flex items-center justify-between">
            <h2 class="text-3xl font-bold" style="font-family:'Cairo',sans-serif;">الأكثر <span
                    class="gold-text">رواجاً</span></h2>
            <a href="#" class="text-sm animated-link" style="color:var(--gold);">عرض الكل →</a>
        </div>
        <div class="h-scroll px-6" style="display:flex; gap:1.2rem; padding-right:1.5rem;">
            <div class="movie-card">
                <img src="https://images.unsplash.com/photo-1518676590629-3dcbd9c5a5c9?w=300&h=400&fit=crop" />
                <div class="overlay"></div>
                <div class="info"><span class="genre-badge">رعب</span>
                    <h4 class="font-bold mt-2 text-sm">الظلام الأبدي</h4>
                    <p class="text-xs" style="color:var(--text-dim);">⭐ 7.9</p>
                    <div class="extra mt-2"><button class="text-xs px-4 py-1 rounded-full"
                            style="background:var(--gold);color:var(--deep);">شاهد</button></div>
                </div>
            </div>
            <div class="movie-card">
                <img src="https://images.unsplash.com/photo-1485846234645-a62644f84728?w=300&h=400&fit=crop" />
                <div class="overlay"></div>
                <div class="info"><span class="genre-badge">كوميديا</span>
                    <h4 class="font-bold mt-2 text-sm">ليلة القمر</h4>
                    <p class="text-xs" style="color:var(--text-dim);">⭐ 8.1</p>
                    <div class="extra mt-2"><button class="text-xs px-4 py-1 rounded-full"
                            style="background:var(--gold);color:var(--deep);">شاهد</button></div>
                </div>
            </div>
            <div class="movie-card">
                <img src="https://images.unsplash.com/photo-1574267432553-4b4628081c31?w=300&h=400&fit=crop" />
                <div class="overlay"></div>
                <div class="info"><span class="genre-badge">مغامرة</span>
                    <h4 class="font-bold mt-2 text-sm">رحلة إلى النهاية</h4>
                    <p class="text-xs" style="color:var(--text-dim);">⭐ 8.5</p>
                    <div class="extra mt-2"><button class="text-xs px-4 py-1 rounded-full"
                            style="background:var(--gold);color:var(--deep);">شاهد</button></div>
                </div>
            </div>
            <div class="movie-card">
                <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=300&h=400&fit=crop" />
                <div class="overlay"></div>
                <div class="info"><span class="genre-badge">موسيقى</span>
                    <h4 class="font-bold mt-2 text-sm">أنغام الروح</h4>
                    <p class="text-xs" style="color:var(--text-dim);">⭐ 8.8</p>
                    <div class="extra mt-2"><button class="text-xs px-4 py-1 rounded-full"
                            style="background:var(--gold);color:var(--deep);">شاهد</button></div>
                </div>
            </div>
            <div class="movie-card">
                <img src="https://images.unsplash.com/photo-1594909122845-11baa439b7bf?w=300&h=400&fit=crop" />
                <div class="overlay"></div>
                <div class="info"><span class="genre-badge">خيال علمي</span>
                    <h4 class="font-bold mt-2 text-sm">كوكب المجهول</h4>
                    <p class="text-xs" style="color:var(--text-dim);">⭐ 9.0</p>
                    <div class="extra mt-2"><button class="text-xs px-4 py-1 rounded-full"
                            style="background:var(--gold);color:var(--deep);">شاهد</button></div>
                </div>
            </div>
            <div class="movie-card">
                <img src="https://images.unsplash.com/photo-1512070679279-8988d32161be?w=300&h=400&fit=crop" />
                <div class="overlay"></div>
                <div class="info"><span class="genre-badge">رومانسي</span>
                    <h4 class="font-bold mt-2 text-sm">قلب على قلب</h4>
                    <p class="text-xs" style="color:var(--text-dim);">⭐ 7.6</p>
                    <div class="extra mt-2"><button class="text-xs px-4 py-1 rounded-full"
                            style="background:var(--gold);color:var(--deep);">شاهد</button></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section class="py-24 px-6 reveal"
        style="background:linear-gradient(to bottom, transparent, rgba(201,168,76,0.03), transparent);">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-3" style="font-family:'Cairo',sans-serif;">لماذا <span
                        class="gold-text">البصيرة؟</span></h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="feature-card p-7 text-center">
                    <div class="text-4xl mb-4">🎬</div>
                    <h3 class="text-lg font-bold mb-2">جودة 4K HDR</h3>
                    <p class="text-sm" style="color:var(--text-dim);">استمتع بصورة فائقة الوضوح وألوان حقيقية في كل
                        لحظة</p>
                </div>
                <div class="feature-card p-7 text-center">
                    <div class="text-4xl mb-4">📥</div>
                    <h3 class="text-lg font-bold mb-2">تحميل بدون إنترنت</h3>
                    <p class="text-sm" style="color:var(--text-dim);">حمّل ما يعجبك وشاهده في أي وقت حتى بدون اتصال
                    </p>
                </div>
                <div class="feature-card p-7 text-center">
                    <div class="text-4xl mb-4">🌍</div>
                    <h3 class="text-lg font-bold mb-2">محتوى متعدد اللغات</h3>
                    <p class="text-sm" style="color:var(--text-dim);">ترجمات وأصوات بـ +40 لغة عالمية لكل المحتوى</p>
                </div>
                <div class="feature-card p-7 text-center">
                    <div class="text-4xl mb-4">👨‍👩‍👧</div>
                    <h3 class="text-lg font-bold mb-2">ملفات شخصية متعددة</h3>
                    <p class="text-sm" style="color:var(--text-dim);">حساب واحد للعائلة كلها مع تجربة مخصصة لكل فرد
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRICING ===== -->
    <section class="py-24 px-6 reveal">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-3" style="font-family:'Cairo',sans-serif;">اختر <span
                        class="gold-text">خطتك</span></h2>
                <p style="color:var(--text-dim);">ابدأ مجاناً لمدة 30 يوم. ألغِ في أي وقت.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">

                <div class="price-card p-8">
                    <h3 class="text-xl font-bold mb-1">الأساسية</h3>
                    <p class="text-sm mb-6" style="color:var(--text-dim);">للبداية والتجربة</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold" style="font-family:'Cairo',sans-serif;">$4.99</span>
                        <span style="color:var(--text-dim);">/شهر</span>
                    </div>
                    <div class="gold-line mb-6"></div>
                    <ul class="text-sm space-y-3 mb-8" style="color:var(--text-dim);">
                        <li>✓ جودة HD</li>
                        <li>✓ شاشة واحدة</li>
                        <li>✓ ملف شخصي</li>
                    </ul>
                    <button class="w-full py-3 rounded-full border font-bold"
                        style="border-color:var(--gold); color:var(--gold);">ابدأ الآن</button>
                </div>

                <div class="price-card featured p-8 pt-14">
                    <h3 class="text-xl font-bold mb-1">المميزة</h3>
                    <p class="text-sm mb-6" style="color:var(--text-dim);">للمشاهد الحقيقي</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold gold-text"
                            style="font-family:'Cairo',sans-serif;">$9.99</span>
                        <span style="color:var(--text-dim);">/شهر</span>
                    </div>
                    <div class="gold-line mb-6"></div>
                    <ul class="text-sm space-y-3 mb-8">
                        <li>✓ جودة 4K HDR</li>
                        <li>✓ شاشتان في آنٍ واحد</li>
                        <li>✓ تحميل بدون إنترنت</li>
                        <li>✓ 5 ملفات شخصية</li>
                    </ul>
                    <button class="w-full py-3 rounded-full font-bold"
                        style="background:var(--gold); color:var(--deep);">ابدأ الآن</button>
                </div>

                <div class="price-card p-8">
                    <h3 class="text-xl font-bold mb-1">العائلية</h3>
                    <p class="text-sm mb-6" style="color:var(--text-dim);">للعائلة بأسرها</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold" style="font-family:'Cairo',sans-serif;">$14.99</span>
                        <span style="color:var(--text-dim);">/شهر</span>
                    </div>
                    <div class="gold-line mb-6"></div>
                    <ul class="text-sm space-y-3 mb-8" style="color:var(--text-dim);">
                        <li>✓ جودة 4K HDR</li>
                        <li>✓ 4 شاشات في آنٍ واحد</li>
                        <li>✓ تحميل غير محدود</li>
                        <li>✓ 10 ملفات شخصية</li>
                        <li>✓ تقييد محتوى الأطفال</li>
                    </ul>
                    <button class="w-full py-3 rounded-full border font-bold"
                        style="border-color:var(--gold); color:var(--gold);">ابدأ الآن</button>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== APP DOWNLOAD ===== -->
    <section class="py-24 px-6 reveal" id="app-section">
        <div class="max-w-7xl mx-auto">
            <div class="app-section-card"
                style="
      background: linear-gradient(135deg, var(--card) 0%, rgba(201,168,76,0.07) 100%);
      border: 1px solid rgba(201,168,76,0.18);
      border-radius: 32px;
      overflow: hidden;
      position: relative;
    ">
                <!-- Decorative glow -->
                <div
                    style="position:absolute;top:-60px;left:-60px;width:300px;height:300px;background:radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 70%);pointer-events:none;">
                </div>
                <div
                    style="position:absolute;bottom:-40px;right:-40px;width:200px;height:200px;background:radial-gradient(circle, rgba(201,168,76,0.08) 0%, transparent 70%);pointer-events:none;">
                </div>

                <div class="grid lg:grid-cols-2 gap-0 items-center">

                    <!-- Text side -->
                    <div class="p-12 lg:p-16 relative z-10">
                        <div class="genre-badge mb-6">📱 متاح الآن</div>
                        <h2 class="text-4xl lg:text-5xl font-bold mb-5 leading-tight"
                            style="font-family:'Cairo',sans-serif;">
                            البصيرة في جيبك<br />
                            <span class="gold-text">في أي مكان</span>
                        </h2>
                        <p class="text-lg mb-10 leading-relaxed" style="color:var(--text-dim);">
                            حمّل التطبيق وشاهد أفضل المحتوى على هاتفك أو تابلت بجودة استثنائية. تجربة مصممة بعناية لراحة
                            عينيك.
                        </p>

                        <!-- App buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">

                            <!-- App Store -->
                            <a href="#" class="app-btn"
                                style="
              display: flex; align-items: center; gap: 14px;
              background: #000;
              border: 1px solid rgba(255,255,255,0.15);
              border-radius: 16px;
              padding: 14px 24px;
              text-decoration: none;
              color: white;
              transition: all 0.3s cubic-bezier(0.23,1,0.32,1);
              min-width: 200px;
            ">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
                                    <path
                                        d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98l-.09.06c-.22.15-2.18 1.29-2.16 3.84.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.24M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" />
                                </svg>
                                <div>
                                    <div style="font-size:0.65rem; color:rgba(255,255,255,0.6); margin-bottom:2px;">
                                        حمّل على</div>
                                    <div style="font-size:1.1rem; font-weight:700; font-family:'Cairo',sans-serif;">App
                                        Store</div>
                                </div>
                            </a>

                            <!-- Google Play -->
                            <a href="#" class="app-btn"
                                style="
              display: flex; align-items: center; gap: 14px;
              background: #000;
              border: 1px solid rgba(255,255,255,0.15);
              border-radius: 16px;
              padding: 14px 24px;
              text-decoration: none;
              color: white;
              transition: all 0.3s cubic-bezier(0.23,1,0.32,1);
              min-width: 200px;
            ">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M3.18 23.76c.3.17.65.19.97.06L14.72 12 3.06.16C2.78.29 2.6.57 2.6.9V23.1c0 .26.22.52.58.66z"
                                        fill="#4CAF50" />
                                    <path
                                        d="M21.54 10.27l-3.04-1.75L14.72 12l3.78 3.48 3.04-1.75c.86-.5.86-1.96 0-2.46z"
                                        fill="#FFD600" />
                                    <path d="M3.18.24 14.72 12 18.5 8.52 4.15.06A1.1 1.1 0 003.18.24z"
                                        fill="#FF3D00" />
                                    <path d="M3.18 23.76l1-.58L18.5 15.48 14.72 12 3.18 23.76z" fill="#00BCD4" />
                                </svg>
                                <div>
                                    <div style="font-size:0.65rem; color:rgba(255,255,255,0.6); margin-bottom:2px;">
                                        احصل عليه من</div>
                                    <div style="font-size:1.1rem; font-weight:700; font-family:'Cairo',sans-serif;">
                                        Google Play</div>
                                </div>
                            </a>

                        </div>

                        <!-- Mini features -->
                        <div class="flex gap-8 mt-10">
                            <div class="text-center">
                                <div class="text-2xl font-bold gold-text" style="font-family:'Cairo',sans-serif;">4.9
                                </div>
                                <div class="text-xs mt-1" style="color:var(--text-dim);">⭐ تقييم المتجر</div>
                            </div>
                            <div class="w-px" style="background:rgba(201,168,76,0.2);"></div>
                            <div class="text-center">
                                <div class="text-2xl font-bold gold-text" style="font-family:'Cairo',sans-serif;">5M+
                                </div>
                                <div class="text-xs mt-1" style="color:var(--text-dim);">تحميل</div>
                            </div>
                            <div class="w-px" style="background:rgba(201,168,76,0.2);"></div>
                            <div class="text-center">
                                <div class="text-2xl font-bold gold-text" style="font-family:'Cairo',sans-serif;">
                                    مجاني</div>
                                <div class="text-xs mt-1" style="color:var(--text-dim);">التحميل</div>
                            </div>
                        </div>
                    </div>

                    <!-- Phone mockup side -->
                    <div class="relative flex justify-center items-end h-full pb-0 pt-12 lg:pt-0 overflow-hidden"
                        style="min-height:480px;">

                        <!-- Phone 1 (main, center) -->
                        <div class="phone-mockup"
                            style="
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 180px;
            background: #0a0a14;
            border-radius: 36px;
            border: 2px solid rgba(201,168,76,0.4);
            box-shadow: 0 40px 80px rgba(0,0,0,0.7), 0 0 40px rgba(201,168,76,0.1);
            overflow: hidden;
            z-index: 3;
            animation: phone-float 5s ease-in-out infinite;
          ">
                            <!-- Notch -->
                            <div
                                style="width:60px;height:10px;background:#000;border-radius:0 0 12px 12px;margin:6px auto;">
                            </div>
                            <!-- Screen content -->
                            <div style="padding:8px; background: linear-gradient(160deg, #0f0f1a, #08080f);">
                                <div style="border-radius:12px;overflow:hidden;margin-bottom:8px;">
                                    <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=300&h=200&fit=crop"
                                        style="width:100%;display:block;" />
                                </div>
                                <div style="padding:4px 0;">
                                    <div
                                        style="font-size:9px;color:var(--gold-light);margin-bottom:4px;font-family:'Tajawal',sans-serif;">
                                        يُشاهَد الآن</div>
                                    <div style="display:flex;gap:6px;margin-bottom:6px;">
                                        <div style="flex:1;border-radius:8px;overflow:hidden;height:60px;"><img
                                                src="https://images.unsplash.com/photo-1485846234645-a62644f84728?w=120&h=80&fit=crop"
                                                style="width:100%;height:100%;object-fit:cover;" /></div>
                                        <div style="flex:1;border-radius:8px;overflow:hidden;height:60px;"><img
                                                src="https://images.unsplash.com/photo-1518676590629-3dcbd9c5a5c9?w=120&h=80&fit=crop"
                                                style="width:100%;height:100%;object-fit:cover;" /></div>
                                    </div>
                                    <!-- Play bar -->
                                    <div
                                        style="background:rgba(201,168,76,0.2);border-radius:4px;height:3px;margin-bottom:4px;">
                                        <div style="background:var(--gold);width:40%;height:100%;border-radius:4px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Home bar -->
                            <div
                                style="width:50px;height:4px;background:rgba(255,255,255,0.15);border-radius:2px;margin:8px auto 10px;">
                            </div>
                        </div>

                        <!-- Phone 2 (left, tilted) -->
                        <div
                            style="
            position:absolute;
            bottom: 20px;
            left: calc(50% - 150px);
            width: 140px;
            background: #0a0a14;
            border-radius: 28px;
            border: 1.5px solid rgba(201,168,76,0.2);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            overflow: hidden;
            z-index: 2;
            transform: rotate(-8deg);
            opacity: 0.85;
            animation: phone-float2 5.5s ease-in-out infinite;
          ">
                            <div
                                style="width:46px;height:8px;background:#000;border-radius:0 0 10px 10px;margin:5px auto;">
                            </div>
                            <div style="padding:6px;">
                                <img src="https://images.unsplash.com/photo-1620641788421-7a1c342ea42e?w=200&h=300&fit=crop"
                                    style="width:100%;border-radius:10px;display:block;" />
                            </div>
                            <div
                                style="width:40px;height:3px;background:rgba(255,255,255,0.1);border-radius:2px;margin:6px auto 8px;">
                            </div>
                        </div>

                        <!-- Phone 3 (right, tilted) -->
                        <div
                            style="
            position:absolute;
            bottom: 20px;
            left: calc(50% + 30px);
            width: 140px;
            background: #0a0a14;
            border-radius: 28px;
            border: 1.5px solid rgba(201,168,76,0.2);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            overflow: hidden;
            z-index: 2;
            transform: rotate(8deg);
            opacity: 0.85;
            animation: phone-float3 6s ease-in-out infinite;
          ">
                            <div
                                style="width:46px;height:8px;background:#000;border-radius:0 0 10px 10px;margin:5px auto;">
                            </div>
                            <div style="padding:6px;">
                                <img src="https://images.unsplash.com/photo-1574267432553-4b4628081c31?w=200&h=300&fit=crop"
                                    style="width:100%;border-radius:10px;display:block;" />
                            </div>
                            <div
                                style="width:40px;height:3px;background:rgba(255,255,255,0.1);border-radius:2px;margin:6px auto 8px;">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @keyframes phone-float {

            0%,
            100% {
                transform: translateX(-50%) translateY(0px);
            }

            50% {
                transform: translateX(-50%) translateY(-14px);
            }
        }

        @keyframes phone-float2 {

            0%,
            100% {
                transform: rotate(-8deg) translateY(0px);
            }

            50% {
                transform: rotate(-8deg) translateY(-10px);
            }
        }

        @keyframes phone-float3 {

            0%,
            100% {
                transform: rotate(8deg) translateY(0px);
            }

            50% {
                transform: rotate(8deg) translateY(-12px);
            }
        }

        .app-btn:hover {
            border-color: rgba(201, 168, 76, 0.5) !important;
            background: #111 !important;
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
        }
    </style>

    <!-- ===== NEWSLETTER ===== -->
    <section class="py-20 px-6 reveal">
        <div class="max-w-2xl mx-auto text-center">
            <div class="feature-card p-12">
                <div class="text-5xl mb-4">🎬</div>
                <h2 class="text-3xl font-bold mb-3" style="font-family:'Cairo',sans-serif;">كن أول من <span
                        class="gold-text">يعلم</span></h2>
                <p class="mb-8" style="color:var(--text-dim);">اشترك ليصلك آخر الإضافات والعروض الحصرية مباشرة إلى
                    بريدك</p>
                <div class="flex gap-3 max-w-md mx-auto">
                    <input type="email" placeholder="بريدك الإلكتروني..." class="flex-1 text-right" />
                    <button class="px-6 py-3 rounded-full font-bold whitespace-nowrap flex-shrink-0"
                        style="background:var(--gold); color:var(--deep);">اشترك</button>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
  @include('layouts.web.footer')

    <script>
        // Custom Cursor - Fixed
        const cursor = document.getElementById('cursor');
        const trail = document.getElementById('cursorTrail');
        let mx = 0,
            my = 0,
            tx = 0,
            ty = 0;

        document.addEventListener('mousemove', e => {
            mx = e.clientX;
            my = e.clientY;
            cursor.style.transform = `translate(${mx - 5}px, ${my - 5}px)`;
        });

        function animateTrail() {
            tx += (mx - tx) * 0.1;
            ty += (my - ty) * 0.1;
            trail.style.transform = `translate(${tx - 18}px, ${ty - 18}px)`;
            requestAnimationFrame(animateTrail);
        }
        animateTrail();

        // Hover effects on interactive elements
        const interactives = document.querySelectorAll('a, button, .cat-pill, .movie-card, .price-card, .feature-card');
        interactives.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.classList.add('hovering');
                trail.classList.add('hovering');
            });
            el.addEventListener('mouseleave', () => {
                cursor.classList.remove('hovering');
                trail.classList.remove('hovering');
            });
        });

        // Stars
        const starsContainer = document.getElementById('stars');
        for (let i = 0; i < 80; i++) {
            const s = document.createElement('div');
            s.className = 'star';
            s.style.cssText = `
      left: ${Math.random() * 100}%;
      top: ${Math.random() * 100}%;
      --dur: ${2 + Math.random() * 4}s;
      --delay: ${Math.random() * 5}s;
      --max-op: ${0.3 + Math.random() * 0.7};
    `;
            starsContainer.appendChild(s);
        }

        // Reveal on scroll
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) e.target.classList.add('visible');
            });
        }, {
            threshold: 0.1
        });
        reveals.forEach(r => observer.observe(r));

        // Category pills
        document.querySelectorAll('.cat-pill').forEach(pill => {
            pill.addEventListener('click', () => {
                document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
            });
        });
    </script>
</body>

</html>
