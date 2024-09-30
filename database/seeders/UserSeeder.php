<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
             // Create a single user with specific details
             User::factory()->create([
                'full_name' => 'Omar Kaialy',
                'user_name' => 'OmarLord1221',
                'email' => 'omar12kaialy@example.com',
                'password' => Hash::make('12345678'), // Set a specific password
            ]);
    
            // Create multiple random users
            User::factory()->count(10)->create();
    }
}
