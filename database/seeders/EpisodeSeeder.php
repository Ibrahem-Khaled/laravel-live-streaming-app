<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Episode;
use Illuminate\Database\Seeder;

class EpisodeSeeder extends Seeder
{
    /**
     * روابط بث تجريبية للحلقات.
     */
    private array $demoStreams = [
        'https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8',
        'https://www.w3schools.com/html/mov_bbb.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
        'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
    ];

    public function run(): void
    {
        $series = Content::where('type', Content::TYPE_SERIES)->get();

        foreach ($series as $index => $content) {
            $episodesCount = $index === 0 ? 5 : 3;
            for ($ep = 1; $ep <= $episodesCount; $ep++) {
                Episode::create([
                    'content_id' => $content->id,
                    'season_number' => 1,
                    'episode_number' => $ep,
                    'title' => "الحلقة {$ep}",
                    'description' => "وصف الحلقة {$ep} من المسلسل التجريبي",
                    'stream_url' => $this->demoStreams[($ep - 1) % count($this->demoStreams)],
                    'duration' => 45 * 60, // ثانية
                    'sort_order' => $ep,
                ]);
            }
        }
    }
}
