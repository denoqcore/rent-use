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
                ['name_en' => 'Vans',        'name_ro' => 'Dube',           'name_ru' => 'Фургоны',         'slug' => 'vans'],
                ['name_en' => 'Trailers',    'name_ro' => 'Remorci',        'name_ru' => 'Прицепы',         'slug' => 'trailers'],
                ['name_en' => 'Watercraft',  'name_ro' => 'Ambarcațiuni',   'name_ru' => 'Водный транспорт', 'slug' => 'watercraft'],
                ['name_en' => 'Electric transport',  'name_ro' => 'Transport electrice',  'name_ru' => 'Электротранспорт',       'slug' => 'electric-transport'],
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
                ['name_en' => 'Audio',             'name_ro' => 'Audio',                  'name_ru' => 'Аудио',              'slug' => 'audio'],
                ['name_en' => 'Video',             'name_ro' => 'Video',                  'name_ru' => 'Видео',              'slug' => 'video'],
                ['name_en' => 'Cameras',            'name_ro' => 'Camere foto',           'name_ru' => 'Камеры',            'slug' => 'cameras'],
                ['name_en' => 'Gaming consoles',    'name_ro' => 'Console de jocuri',     'name_ru' => 'Игровые консоли',    'slug' => 'gaming-consoles'],
                ['name_en' => 'Projectors',         'name_ro' => 'Proiectoare',           'name_ru' => 'Проекторы',          'slug' => 'projectors'],
                ['name_en' => 'Accessories',        'name_ro' => 'Accesorii',             'name_ru' => 'Аксессуары',          'slug' => 'electronics-accessories'],
                ['name_en' => 'Other electronics', 'name_ro' => 'Alte electronice',       'name_ru' => 'Другая электроника', 'slug' => 'electronics-other'],
            ],
        ],
        [
                'name_en' => 'Music',
                'name_ro' => 'Muzical',
                'name_ru' => 'Музыкальные инструменты',
                'icon'    => 'heroicon-o-musical-note',
                'slug'    => 'music-equipment',
                'children' => [
                    ['name_en' => 'Speakers & Sound Systems', 'name_ro' => 'Boxe și sisteme audio', 'name_ru' => 'Колонки и аудиосистемы', 'slug' => 'speakers-sound-systems'],
                    ['name_en' => 'DJ Equipment',            'name_ro' => 'Echipament DJ',         'name_ru' => 'DJ оборудование',       'slug' => 'dj-equipment'],
                    ['name_en' => 'Microphones',             'name_ro' => 'Microfoane',           'name_ru' => 'Микрофоны',            'slug' => 'microphones'],
                    ['name_en' => 'Mixers & Consoles',       'name_ro' => 'Mixere și console',     'name_ru' => 'Микшеры и консоли',    'slug' => 'mixers-consoles'],
                    ['name_en' => 'Musical Instruments',     'name_ro' => 'Instrumente muzicale',  'name_ru' => 'Музыкальные инструменты', 'slug' => 'musical-instruments'],
                    ['name_en' => 'Studio Equipment',        'name_ro' => 'Echipament studio',     'name_ru' => 'Студийное оборудование', 'slug' => 'studio-equipment'],
                    ['name_en' => 'Lighting & Effects',      'name_ro' => 'Lumini și efecte',      'name_ru' => 'Свет и эффекты',       'slug' => 'lighting-effects'],
                    ['name_en' => 'Cables & Accessories',    'name_ro' => 'Cabluri și accesorii',  'name_ru' => 'Кабели и аксессуары',  'slug' => 'music-accessories'],
                ],
        ],
        [
                'name_en' => 'Sports Equipment',
                'name_ro' => 'Echipament sportiv',
                'name_ru' => 'Спортивное оборудование',
                'icon'    => 'heroicon-o-trophy',
                'slug'    => 'sports-equipment',
                'children' => [
                    ['name_en' => 'Fitness Equipment',      'name_ro' => 'Fitness',            'name_ru' => 'Фитнес оборудование',   'slug' => 'fitness-equipment'],
                    ['name_en' => 'Football Gear',          'name_ro' => 'Fotbal',             'name_ru' => 'Футбол',               'slug' => 'football-gear'],
                    ['name_en' => 'Basketball Equipment',   'name_ro' => 'Baschet',            'name_ru' => 'Баскетбол',            'slug' => 'basketball-equipment'],
                    ['name_en' => 'Winter Sports',          'name_ro' => 'Sporturi de iarnă',  'name_ru' => 'Зимние виды спорта',    'slug' => 'winter-sports'],
                    ['name_en' => 'Water Sports',           'name_ro' => 'Sporturi nautice',   'name_ru' => 'Водные виды спорта',    'slug' => 'water-sports'],
                    ['name_en' => 'Sports Accessories',     'name_ro' => 'Accesorii sportive',  'name_ru' => 'Спортивные аксессуары', 'slug' => 'sports-accessories'],
                    ['name_en' => 'Pools',                   'name_ro' => 'Piscine',             'name_ru' => 'Бассейны',             'slug' => 'pools'],
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
                'name_en' => 'Tourism',
                'name_ro' => 'Turism',
                'name_ru' => 'Туризм',
                'icon'    => 'heroicon-o-globe-europe-africa',
                'slug'    => 'kids',
                'children' => [
                    ['name_en' => 'Tents',             'name_ro' => 'Corturi',            'name_ru' => 'Палатки',            'slug' => 'tents'],
                    ['name_en' => 'Bicycles',          'name_ro' => 'Biciclete',          'name_ru' => 'Велосипеды',         'slug' => 'bicycles'],
                    ['name_en' => 'Fishing gear',      'name_ro' => 'Echipament pescuit', 'name_ru' => 'Рыбалка',            'slug' => 'fishing-gear'],
                    ['name_en' => 'Sleeping bags',     'name_ro' => 'Sacuri de dormit',   'name_ru' => 'Спальные мешки',    'slug' => 'sleeping-bags'],
                    ['name_en' => 'Camping furniture', 'name_ro' => 'Mobilier camping',   'name_ru' => 'Кемпинговая мебель', 'slug' => 'camping-furniture'],
                    ['name_en' => 'Other',             'name_ro' => 'Altele',             'name_ru' => 'Другое',             'slug' => 'baby-other'],
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
        [
                'name_en' => 'Miscellaneous',
                'name_ro' => 'Diverse',
                'name_ru' => 'Разное',
                'icon'    => 'heroicon-o-squares-2x2',
                'slug'    => 'miscellaneous',
                'children' => [
                     ['name_en' => 'Other', 'name_ro' => 'Altele', 'name_ru' => 'Другое', 'slug' => 'miscellaneous-other'],
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

            foreach ($data['children'] ?? [] as $child) {
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
