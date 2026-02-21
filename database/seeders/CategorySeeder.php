<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * تشغيل السيدر ببيانات وهمية للفئات (مع فئات فرعية).
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'رياضة',
                'slug' => 'sports',
                'description' => 'قنوات ومسابقات رياضية',
                'content_type' => 'live',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
                'children' => [
                    ['name' => 'كرة قدم', 'slug' => 'football', 'content_type' => 'live', 'sort_order' => 1],
                    ['name' => 'كورة عالمية', 'slug' => 'world-football', 'content_type' => 'live', 'sort_order' => 2],
                    ['name' => 'دوري المصري', 'slug' => 'egyptian-league', 'content_type' => 'live', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'أفلام',
                'slug' => 'movies',
                'description' => 'أفلام عربية وأجنبية',
                'content_type' => 'movie',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
                'children' => [
                    ['name' => 'أفلام عربية', 'slug' => 'arabic-movies', 'content_type' => 'movie', 'sort_order' => 1],
                    ['name' => 'أفلام أجنبية', 'slug' => 'foreign-movies', 'content_type' => 'movie', 'sort_order' => 2],
                    ['name' => 'أفلام أكشن', 'slug' => 'action-movies', 'content_type' => 'movie', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'مسلسلات',
                'slug' => 'series',
                'description' => 'مسلسلات ودراما',
                'content_type' => 'series',
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => false,
                'children' => [
                    ['name' => 'مسلسلات عربية', 'slug' => 'arabic-series', 'content_type' => 'series', 'sort_order' => 1],
                    ['name' => 'مسلسلات تركية', 'slug' => 'turkish-series', 'content_type' => 'series', 'sort_order' => 2],
                    ['name' => 'مسلسلات أجنبية', 'slug' => 'foreign-series', 'content_type' => 'series', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'قنوات عامة',
                'slug' => 'general-channels',
                'description' => 'قنوات تلفزيونية عامة',
                'content_type' => 'channel',
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => false,
                'children' => [],
            ],
        ];

        foreach ($categories as $data) {
            $children = $data['children'] ?? [];
            unset($data['children']);

            $parent = Category::create($data);

            foreach ($children as $i => $child) {
                $childData = array_merge($child, [
                    'parent_id' => $parent->id,
                    'sort_order' => $child['sort_order'] ?? ($i + 1),
                    'is_active' => true,
                ]);
                Category::create($childData);
            }
        }
    }
}
