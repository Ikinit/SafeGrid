<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Typhoon Safety Infographic',
                'image_path' => 'https://www.raket.ph/paul.digital/products/infographics-typhoon?srsltid=AfmBOorDURFiRzcQeBHL0s5Im1irvTQPYrHyJEn5t1m2Wc8YqsU0xnkX',
                'description' => 'Essential steps to take before, during, and after a major typhoon.'
            ],
            [
                'title' => 'Earthquake "Drop, Cover, Hold"',
                'image_path' => 'https://ph.pinterest.com/pin/quick-saves--1477812371554416/',
                'description' => 'Visual guide on how to protect yourself during seismic activity.'
            ],
            [
                'title' => 'Flood Evacuation Routes',
                'image_path' => 'https://www.facebook.com/thefilipinohomeschooler/posts/this-is-a-simple-infographic-for-kids-to-learn-flood-safety-tipsstay-safe-and-dr/1153214320182895/',
                'description' => 'Understanding local flood markers and safe zones.'
            ]
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}