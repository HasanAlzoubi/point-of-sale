<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user=User::create([
            'first_name' => 'super',
            'last_name' => 'admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $user->attachRole('super_admin');

        $users = User::factory(5)->create();
        foreach ($users as $user){
            $user->attachRole('admin');
        }

    }
}
