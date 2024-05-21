<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('app_settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $appSetting = [
            [
                'name'=> 'authorize login',
                'slug' => Str::slug('authorize login'),
                'status' => 0
            ],

            [
                'name'=> 'override bssid',
                'slug' => Str::slug('override bssid'),
                'status' => 0
            ],

            [
                'name'=> '24 hour format',
                'slug' => Str::slug('24 hour format'),
                'status' => 0
            ],

            [
                'name'=> 'Date In BS',
                'slug' => Str::slug('BS'),
                'status' => 0
            ],

            [
                'name'=> 'Dark Theme',
                'slug' => Str::slug('Dark Theme'),
                'status' => 0
            ],

        ];

        DB::table('app_settings')->insert($appSetting);
    }
}
