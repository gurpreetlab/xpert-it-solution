<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ["view-dashboard", "manage-categories"];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                "name" => $permission
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            "name" => "super-admin"
        ]);

        $customer = Role::firstOrCreate([
            "name" => "customer"
        ]);

        $superAdmin->syncPermissions(Permission::all());
    }
}
