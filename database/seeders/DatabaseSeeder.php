<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CompanySetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);


        CompanySetting::create([
            'logo' => '632fd60d2edd4-ecomlogo.png',
            'company_name' => 'MY SHOP',
            'phone_one' => '09123456789',
            'phone_two' => '09123465656',
            'address' => 'Yangon Region, Myanmar',
            'email' => 'myshop@gmail.com',
        ]);


    }
}