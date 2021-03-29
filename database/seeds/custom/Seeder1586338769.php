<?php

namespace Database\Seeds\Custom;

use App\Settings;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class Seeder1586338769 extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $path = base_path() . "/database/seeds/data/seed.json";
        $seedRecords = json_decode(file_get_contents($path), true);

        foreach($seedRecords as $tableName => $tableData) {
            foreach($tableData['records'] as $record) {
                if($tableData['eloquent']) {
                    $newRecord = new $tableData['eloquent'];
                    foreach($record as $key => $value){
                        $newRecord[$key] = $value;
                    }
                    $newRecord->save();
                } else {
                    \DB::table($tableName)->insert($record);
                }
            }
        }

        Settings::update_value('school_name', session()->get('seed_vars.school_name', 'Uteach'));
        Settings::update_value('school_initial', session()->get('seed_vars.school_initial', 'UT'));

        // User & their role
        $username = session()->get('seed_vars.super_admin_username', 'admin');
        $user = User::firstOrNew([
            'username' => $username
        ]);
        $user->name = 'Super Admin';
        $user->email = session()->get('seed_vars.super_admin_email', 'admin@gmail.com');
        $user->username = $username;
        $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->password = Hash::make(session()->get('seed_vars.super_admin_password', 'admin'));
        $user->lang = 'en';
        $user->receive_emails = 1;
        $user->calendar_view = 'agendaWeek';
        $user->stripe_customer_id = NULL;
        $user->save();
        $user->assignRole('Super Admin');

        Schema::enableForeignKeyConstraints();

        $source_dir = base_path() . "/database/seeds/data/files/app/public/*";
        $dest_dir = storage_path().'/app/public/';
        exec('cp -r '. $source_dir .' '.$dest_dir, $sh_output, $sh_status);
        if ($sh_status != 0) {
            \Log::info([
                'exit_status' => $sh_status,
                'output' => $sh_output
            ]);
        }
    }
}