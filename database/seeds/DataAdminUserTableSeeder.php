<?php

use Illuminate\Database\Seeder;

class DataAdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('data_admin_users')->insert([
            'nickname' => 'admin',
            'tel' => env('ADMIN_LOGIN_NAME'),
            'password' => bcrypt(env('ADMIN_LOGIN_PASSWORD')),
        ]);
    }
}
