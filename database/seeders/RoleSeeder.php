<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles1 = Role::create(['name' => 'Admin']);
        $roles2 = Role::create(['name' => 'Persona']);
        
        Permission::create(['name' => 'login','description' => 'Iniciar session'])->syncRoles([$roles1, $roles2]);
        Permission::create(['name' => 'register','description' => 'Registrar nueva cuenta'])->syncRoles([$roles1]);

        // Permission::create(['name' => 'login']);
        // Permission::create(['name' => 'register']);
        
    }
}
