<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'customer']);

        // Create a super admin user
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'info@xpertitsolution.com',
        ]);

        $superAdmin->assignRole('super-admin');

        // Create a customer user
        $customer = User::factory()->create([
            'name' => 'Gurpreet Singh',
            'email' => 'gurpreet@example.com',
        ]);

        $customer->assignRole('customer');
    }
}
