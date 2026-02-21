<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Content;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * روابط بث تجريبية تعمل للمعاينة (Demo streams).
     */
    private array $demoStreams = [
        'https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8', // HLS - Big Buck Bunny
        'https://www.w3schools.com/html/mov_bbb.mp4',         // MP4
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
        'https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8',
    ];

    public function run(): void
    {
        $sportCat = Category::where('name', 'كرة قدم')->first() ?? Category::whereNull('parent_id')->first();
        $movieCat = Category::where('name', 'أفلام عربية')->first() ?? Category::where('content_type', 'movie')->first();
        $seriesCat = Category::where('name', 'مسلسلات عربية')->first() ?? Category::where('content_type', 'series')->first();
        $channelCat = Category::where('name', 'قنوات عامة')->first() ?? Category::whereNull('parent_id')->first();

        // قنوات
        $channels = [
            ['name' => 'بي إن سبورت 1', 'slug' => 'bein-sport-1', 'description' => 'قناة رياضية مشهورة', 'category_id' => $sportCat?->id],
            ['name' => 'قناة الجزيرة', 'slug' => 'aljazeera', 'description' => 'قناة إخبارية', 'category_id' => $channelCat?->id],
            ['name' => 'قناة MBC 1', 'slug' => 'mbc-1', 'description' => 'قناة ترفيهية', 'category_id' => $channelCat?->id],
        ];
        foreach ($channels as $i => $ch) {
            Content::create([
                'name' => $ch['name'],
                'slug' => $ch['slug'],
                'description' => $ch['description'] ?? null,
                'type' => Content::TYPE_CHANNEL,
                'stream_url' => $this->demoStreams[$i % count($this->demoStreams)],
                'category_id' => $ch['category_id'],
                'sort_order' => $i + 1,
                'is_active' => true,
                'is_featured' => $i === 0,
            ]);
        }

        // أفلام
        $movies = [
            ['name' => 'فيلم تجريبي 1', 'slug' => 'demo-movie-1', 'description' => 'فيلم أكشن تجريبي', 'year' => 2024, 'duration' => 120, 'rating' => 8.5, 'quality' => 'HD'],
            ['name' => 'فيلم تجريبي 2', 'slug' => 'demo-movie-2', 'description' => 'فيلم دراما', 'year' => 2023, 'duration' => 95, 'rating' => 7.2, 'quality' => 'FHD'],
            ['name' => 'فيلم تجريبي 3', 'slug' => 'demo-movie-3', 'description' => 'فيلم كوميدي', 'year' => 2024, 'duration' => 100, 'rating' => 6.8, 'quality' => 'HD'],
        ];
        foreach ($movies as $i => $m) {
            Content::create([
                'name' => $m['name'],
                'slug' => $m['slug'],
                'description' => $m['description'] ?? null,
                'type' => Content::TYPE_MOVIE,
                'stream_url' => $this->demoStreams[$i % count($this->demoStreams)],
                'category_id' => $movieCat?->id,
                'sort_order' => $i + 1,
                'is_active' => true,
                'is_featured' => $i === 0,
                'year' => $m['year'] ?? null,
                'duration' => $m['duration'] ?? null,
                'rating' => $m['rating'] ?? null,
                'quality' => $m['quality'] ?? null,
                'language' => 'العربية',
            ]);
        }

        // مسلسلات (بدون stream_url رئيسي، الحلقات لها الروابط)
        $series = [
            ['name' => 'مسلسل تجريبي 1', 'slug' => 'demo-series-1', 'description' => 'مسلسل دراما رمضاني', 'year' => 2024],
            ['name' => 'مسلسل تجريبي 2', 'slug' => 'demo-series-2', 'description' => 'مسلسل كوميدي', 'year' => 2023],
        ];
        foreach ($series as $i => $s) {
            Content::create([
                'name' => $s['name'],
                'slug' => $s['slug'],
                'description' => $s['description'] ?? null,
                'type' => Content::TYPE_SERIES,
                'stream_url' => null,
                'category_id' => $seriesCat?->id,
                'sort_order' => $i + 1,
                'is_active' => true,
                'is_featured' => $i === 0,
                'year' => $s['year'] ?? null,
                'rating' => 7.5,
                'quality' => 'HD',
            ]);
        }

        // بث مباشر
        Content::create([
            'name' => 'مباراة تجريبية مباشرة',
            'slug' => 'demo-live-match',
            'description' => 'بث مباشر لمباراة',
            'type' => Content::TYPE_LIVE,
            'stream_url' => $this->demoStreams[0],
            'category_id' => $sportCat?->id,
            'sort_order' => 1,
            'is_active' => true,
            'is_featured' => true,
        ]);
    }
}
