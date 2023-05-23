<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'update personal information',
            'create submissions',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission]
            );
        }
        $patientRole = Role::findByName(UserRole::Patient->value);
        $patientRole->givePermissionTo('update personal information');
        $patientRole->givePermissionTo('create submissions');
    }
}
