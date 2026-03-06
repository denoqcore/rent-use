<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name_en' => 'Transport',
                'name_ro' => 'Transport',
                'icon'    => 'heroicon-o-truck',
                'slug'    => 'transport',
                'children' => [
                    ['name_en' => 'Cars',        'name_ro' => 'Mașini',       'slug' => 'cars'],
                    ['name_en' => 'Motorcycles', 'name_ro' => 'Motociclete',  'slug' => 'motorcycles'],
                    ['name_en' => 'Trailers',    'name_ro' => 'Remorci',      'slug' => 'trailers'],
                    ['name_en' => 'Watercraft',  'name_ro' => 'Ambarcațiuni', 'slug' => 'watercraft'],
                ],
            ],
            [
                'name_en' => 'Electronics',
                'name_ro' => 'Electronică',
                'icon'    => 'heroicon-o-device-phone-mobile',
                'slug'    => 'electronics',
                'children' => [
                    ['name_en' => 'Audio',            'name_ro' => 'Audio',             'slug' => 'audio'],
                    ['name_en' => 'Video',            'name_ro' => 'Video',             'slug' => 'video'],
                    ['name_en' => 'Other electronics','name_ro' => 'Alte electronice',  'slug' => 'other-electronics'],
                ],
            ],
            [
                'name_en' => 'Tools',
                'name_ro' => 'Unelte',
                'icon'    => 'heroicon-o-wrench-screwdriver',
                'slug'    => 'tools',
                'children' => [
                    ['name_en' => 'Power Tools',   'name_ro' => 'Scule electrice', 'slug' => 'power-tools'],
                    ['name_en' => 'Garden Tools',  'name_ro' => 'Unelte grădină',  'slug' => 'garden-tools'],
                    ['name_en' => 'Construction',  'name_ro' => 'Construcții',     'slug' => 'construction'],
                    ['name_en' => 'Other',         'name_ro' => 'Altele',          'slug' => 'tools-other'],
                ],
            ],
            [
                'name_en' => 'Kids',
                'name_ro' => 'Copii',
                'icon'    => 'heroicon-o-face-smile',
                'slug'    => 'kids',
                'children' => [
                    ['name_en' => 'Toys',          'name_ro' => 'Jucării',         'slug' => 'toys'],
                    ['name_en' => 'Baby gear',     'name_ro' => 'Accesorii bebeluș','slug' => 'baby-gear'],
                    ['name_en' => 'Kids clothing', 'name_ro' => 'Haine copii',     'slug' => 'kids-clothing'],
                ],
            ],
            [
                'name_en' => 'Construction machinery',
                'name_ro' => 'Utilaje construcții',
                'icon'    => 'heroicon-o-cog-6-tooth',
                'slug'    => 'construction-machinery',
                'children' => [
                    ['name_en' => 'Excavators',  'name_ro' => 'Excavatoare',  'slug' => 'excavators'],
                    ['name_en' => 'Cranes',      'name_ro' => 'Macarale',     'slug' => 'cranes'],
                    ['name_en' => 'Compressors', 'name_ro' => 'Compresoare',  'slug' => 'compressors'],
                ],
            ],
        ];

        foreach ($categories as $data) {
            $parent = Category::create([
                'name_en' => $data['name_en'],
                'name_ro' => $data['name_ro'],
                'slug'    => $data['slug'],
                'icon'    => $data['icon'],
            ]);

            foreach ($data['children'] as $child) {
                Category::create([
                    'name_en'   => $child['name_en'],
                    'name_ro'   => $child['name_ro'],
                    'slug'      => $child['slug'],
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
