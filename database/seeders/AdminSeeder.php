<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();

        if(!$admin){
            //create admin user
            User::create([
                'name'=> 'Admin',
                'email'=>'admin@admin.com',
                'phone_number'=>'0900000000',
                'password'=> bcrypt('password'),
                'is_approved'=>true,
                'is_admin'=>true,
            ]);
        }
    }
}
