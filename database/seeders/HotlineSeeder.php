<?php

namespace Database\Seeders;

use App\Models\Hotline;
use Illuminate\Database\Seeder;

class HotlineSeeder extends Seeder
{
    public function run(): void
    {
        $hotlines = [
            // National
            ['name' => 'NDRRMC',         'contact_number' => '(02) 8911-5061', 'location' => 'National',      'category' => 'DRRM'],
            ['name' => 'Red Cross',       'contact_number' => '143',            'location' => 'National',      'category' => 'Medical'],
            ['name' => 'BFP National',    'contact_number' => '(02) 8426-0219', 'location' => 'National',      'category' => 'BFP'],
            ['name' => 'PNP Hotline',     'contact_number' => '117',            'location' => 'National',      'category' => 'PNP'],

            // Cebu City
            ['name' => 'BFP Cebu',        'contact_number' => '(032) 254-2585', 'location' => 'Cebu City',    'category' => 'BFP'],
            ['name' => 'PNP Cebu',        'contact_number' => '(032) 888-5116', 'location' => 'Cebu City',    'category' => 'PNP'],
            ['name' => 'CDRRMO Cebu',     'contact_number' => '(032) 255-1275', 'location' => 'Cebu City',    'category' => 'DRRM'],

            // Tacloban
            ['name' => 'BFP Tacloban',    'contact_number' => '(053) 832-2336', 'location' => 'Tacloban',     'category' => 'BFP'],
            ['name' => 'PNP Tacloban',    'contact_number' => '(053) 832-2333', 'location' => 'Tacloban',     'category' => 'PNP'],
            ['name' => 'CDRRMO Tacloban', 'contact_number' => '(053) 832-0014', 'location' => 'Tacloban',     'category' => 'DRRM'],

            // Manila
            ['name' => 'BFP Manila',      'contact_number' => '(02) 8527-3701', 'location' => 'Manila',       'category' => 'BFP'],
            ['name' => 'PNP Manila',      'contact_number' => '(02) 8523-8856', 'location' => 'Manila',       'category' => 'PNP'],
            ['name' => 'MDRRMO Manila',   'contact_number' => '(02) 8527-9828', 'location' => 'Manila',       'category' => 'DRRM'],
        ];

        foreach ($hotlines as $hotline) {
            Hotline::create($hotline);
        }
    }
}