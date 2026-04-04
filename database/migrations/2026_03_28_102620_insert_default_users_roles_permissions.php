<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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
            'view pendapatan', 'export pendapatan',
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 
    }
};
