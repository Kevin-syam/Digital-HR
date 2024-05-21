<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('general_settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $generalSetting = [
            [
                'name' => 'Firebase Key',
                'type' => 'configuration',
                'key' => 'firebase_key',
                'value' => config('firebase.server_key') ?? "",
                'description' => 'Firebase key is needed to send notification in mobile.'
            ],

            [
                'name' => 'Set Number Of Days for local Push Notification',
                'type' => 'configuration',
                'key' => 'attendance_notify',
                'value' => "7",
                'description' => 'Setting no of days will automatically send the data of those days to the mobile application.Receiving this data on the mobile end will allow the mobile application to set local push notification for those dates. The local push notification will help employees remember to check in on time as well as to check out when the shift is about to end.'
            ],
        ];

        DB::table('general_settings')->insert($generalSetting);

    }

}
