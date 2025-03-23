<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create the user and assign the admin role
        $user = User::create([
            'name' => 'Sandy',
            'email' => 'donator01.balbuena@gmail.com',
            'password' => Hash::make('123123123'), // Use a secure password
            'email_verified_at' => Carbon::now(), // Automatically verify the email
        ]);

        // Assign the admin role to the user
        $user->assignRole($adminRole);

        $this->command->info("✅ Admin Users seeded successfully!");
    }
}
