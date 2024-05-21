<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmployeeAccountSeeder extends Seeder
{
    public function run()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            if (Schema::hasTable('employee_accounts')) {
                DB::table('employee_accounts')->truncate();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $users = DB::table('users')->select(
                'id',
                'bank_name',
                'bank_account_no',
                'bank_account_type',
                'salary'
            )->get();

            $accountDetail = $users->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'bank_name' => $user->bank_name,
                    'bank_account_no' => $user->bank_account_no,
                    'bank_account_type' => $user->bank_account_type,
                    'salary' => $user->salary,
                ];
            })->toArray();

            DB::table('employee_accounts')->insert($accountDetail);

        } catch (Exception $e) {
            dump('~EmployeeAccountDetail', $e->getMessage());
        }
    }
}
