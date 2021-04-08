<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => Str::random(10),
            'email' =>'xLR6TjiPSv@gmail.com',
            'document' => '12312312312',
            'password' => Hash::make('password'),
        ]);
    }
}
