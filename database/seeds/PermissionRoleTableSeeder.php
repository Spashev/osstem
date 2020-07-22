<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'user'
        ]);
        Role::create([
            'name' => 'moderator'
        ]);
        $role = Role::create([
            'name' => 'admin'
        ]);
        
        $permission = Permission::create([
            'name' => 'edit'
        ]);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $permission = Permission::create([
            'name' => 'create'
        ]);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $permission = Permission::create([
            'name' => 'delete'
        ]);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $permission = Permission::create([
            'name' => 'update'
        ]);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);
    }
}
