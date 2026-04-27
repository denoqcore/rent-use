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
            'name_ru' => 'Транспорт',
            'icon'    => 'heroicon-o-truck',
            'slug'    => 'transport',
            'children' => [
                ['name_en' => 'Cars',        'name_ro' => 'Mașini',         'name_ru' => 'Автомобили',      'slug' => 'cars'],
                ['name_en' => 'Motorcycles', 'name_ro' => 'Motociclete',    'name_ru' => 'Мотоциклы',       'slug' => 'motorcycles'],
                ['name_en' => 'Trailers',    'name_ro' => 'Remorci',        'name_ru' => 'Прицепы',         'slug' => 'trailers'],
                ['name_en' => 'Watercraft',  'name_ro' => 'Ambarcațiuni',   'name_ru' => 'Водный транспорт', 'slug' => 'watercraft'],
                ['name_en' => 'Other',       'name_ro' => 'Altele',         'name_ru' => 'Другое',          'slug' => 'transport-other'],
            ],
        ],
        [
            'name_en' => 'Electronics',
            'name_ro' => 'Electronică',
            'name_ru' => 'Электроника',
            'icon'    => 'heroicon-o-device-phone-mobile',
            'slug'    => 'electronics',
            'children' => [
                ['name_en' => 'Audio',             'name_ro' => 'Audio',             'name_ru' => 'Аудио',              'slug' => 'audio'],
                ['name_en' => 'Video',             'name_ro' => 'Video',             'name_ru' => 'Видео',              'slug' => 'video'],
                ['name_en' => 'Other electronics', 'name_ro' => 'Alte electronice',  'name_ru' => 'Другая электроника', 'slug' => 'electronics-other'],
            ],
        ],
        [
            'name_en' => 'Tools',
            'name_ro' => 'Unelte',
            'name_ru' => 'Инструменты',
            'icon'    => 'heroicon-o-wrench-screwdriver',
            'slug'    => 'tools',
            'children' => [
                ['name_en' => 'Power Tools',  'name_ro' => 'Scule electrice', 'name_ru' => 'Электроинструменты', 'slug' => 'power-tools'],
                ['name_en' => 'Garden Tools', 'name_ro' => 'Unelte grădină',  'name_ru' => 'Садовые инструменты', 'slug' => 'garden-tools'],
                ['name_en' => 'Construction', 'name_ro' => 'Construcții',     'name_ru' => 'Строительство',       'slug' => 'construction'],
                ['name_en' => 'Other',        'name_ro' => 'Altele',          'name_ru' => 'Другое',              'slug' => 'tools-other'],
            ],
        ],
        [
            'name_en' => 'Kids',
            'name_ro' => 'Copii',
            'name_ru' => 'Детские товары',
            'icon'    => 'heroicon-o-face-smile',
            'slug'    => 'kids',
            'children' => [
                ['name_en' => 'Toys',          'name_ro' => 'Jucării',            'name_ru' => 'Игрушки',            'slug' => 'toys'],
                ['name_en' => 'Baby gear',     'name_ro' => 'Accesorii bebeluș',  'name_ru' => 'Товары для малышей', 'slug' => 'baby-gear'],
                ['name_en' => 'Kids clothing', 'name_ro' => 'Haine copii',        'name_ru' => 'Детская одежда',     'slug' => 'kids-clothing'],
                ['name_en' => 'Other',         'name_ro' => 'Altele',             'name_ru' => 'Другое',             'slug' => 'baby-other'],
            ],
        ],
        [
            'name_en' => 'Construction machinery',
            'name_ro' => 'Utilaje construcții',
            'name_ru' => 'Строительная техника',
            'icon'    => 'heroicon-o-cog-6-tooth',
            'slug'    => 'construction-machinery',
            'children' => [
                ['name_en' => 'Excavators',  'name_ro' => 'Excavatoare', 'name_ru' => 'Экскаваторы',  'slug' => 'excavators'],
                ['name_en' => 'Cranes',      'name_ro' => 'Macarale',    'name_ru' => 'Краны',        'slug' => 'cranes'],
                ['name_en' => 'Compressors', 'name_ro' => 'Compresoare', 'name_ru' => 'Компрессоры',  'slug' => 'compressors'],
                ['name_en' => 'Other',       'name_ro' => 'Altele',      'name_ru' => 'Другое',       'slug' => 'construction-other'],
            ],
            ],
        ];

        foreach ($categories as $data) {
            $parent = Category::updateOrCreate([
                'name_en' => $data['name_en'],
                'name_ro' => $data['name_ro'],
                'name_ru' => $data['name_ru'],
                'slug'    => $data['slug'],
                'icon'    => $data['icon'],
            ]);

            foreach ($data['children'] as $child) {
                Category::updateOrCreate([
                    'name_en'   => $child['name_en'],
                    'name_ro'   => $child['name_ro'],
                    'name_ru'   => $child['name_ru'],
                    'slug'      => $child['slug'],
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
