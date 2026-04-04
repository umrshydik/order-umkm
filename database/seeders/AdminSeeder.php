<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'view kategori', 'create kategori', 'edit kategori', 'delete kategori',
            'view produk', 'create produk', 'edit produk', 'delete produk',
            'view pesanan', 'create pesanan', 'edit pesanan', 'delete pesanan',
            'view pengguna', 'create pengguna', 'edit pengguna', 'delete pengguna',
            'view hak akses', 'create hak akses', 'edit hak akses', 'delete hak akses',
            'view permission', 'create permission', 'edit permission', 'delete permission',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign created permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // create admin user
        $user = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'), // password
            'role' => 'admin' // fallback to the old role column just in case
        ]);
        
        $user->assignRole($roleAdmin);
    }
}
