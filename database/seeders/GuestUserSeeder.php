<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class GuestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the admin role if it doesn't exist
        $guestRole = Role::firstOrCreate(['name' => 'guest']);

        // Create the user and assign the admin role
        $user = User::create([
            'name' => 'Cathy',
            'email' => 'cathy20.balbuena@gmail.com',
            'password' => Hash::make('123123123'), // Use a secure password
            'email_verified_at' => Carbon::now(), // Automatically verify the email
        ]);

        // Assign the admin role to the user
        $user->assignRole($guestRole);

        $this->command->info("✅ Guest Users seeded successfully!");
    }
}
