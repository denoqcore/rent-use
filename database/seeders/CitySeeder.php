<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'name_ru' => 'Кишинёв',
                'name_ro' => 'Chișinău',
                'name_en' => 'Chisinau',
                'is_suburb' => false,
                'order' => 1,
            ],
            [
                'name_ru' => 'Бельцы',
                'name_ro' => 'Bălți',
                'name_en' => 'Balti',
                'is_suburb' => false,
                'order' => 2,
            ],
            [
                'name_ru' => 'Кагул',
                'name_ro' => 'Cahul',
                'name_en' => 'Cahul',
                'is_suburb' => false,
                'order' => 3,
            ],
            [
                'name_ru' => 'Оргеев',
                'name_ro' => 'Orhei',
                'name_en' => 'Orhei',
                'is_suburb' => false,
                'order' => 4,
            ],
            [
                'name_ru' => 'Комрат',
                'name_ro' => 'Comrat',
                'name_en' => 'Comrat',
                'is_suburb' => false,
                'order' => 5,
            ],

            // пригороды
            [
                'name_ru' => 'Дурлешты',
                'name_ro' => 'Durlești',
                'name_en' => 'Durlești',
                'is_suburb' => true,
                'order' => 10,
            ],
            [
                'name_ru' => 'Кодру',
                'name_ro' => 'Codru',
                'name_en' => 'Codru',
                'is_suburb' => true,
                'order' => 11,
            ],
            [
                'name_ru' => 'Ставчены',
                'name_ro' => 'Stăuceni',
                'name_en' => 'Stauceni',
                'is_suburb' => true,
                'order' => 12,
            ],
        ];

        foreach ($cities as $city) {
            DB::table('cities')->updateOrInsert(
                ['slug' => Str::slug($city['name_en'])],
                [
                    'name_ru' => $city['name_ru'],
                    'name_ro' => $city['name_ro'],
                    'name_en' => $city['name_en'],
                    'is_suburb' => $city['is_suburb'],
                    'order' => $city['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
