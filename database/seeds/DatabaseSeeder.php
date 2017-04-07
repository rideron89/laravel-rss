<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'rideron89@gmail.com',
            'password' => bcrypt('bob'),
            'real_name' => 'Ron Rider',
        ]);
    }
}
