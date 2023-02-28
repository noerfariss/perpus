<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Umum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProvinsiSeeder::class,
            KotaSeeder::class,
            KecamatanSeeder::class
        ]);

        $user = User::create([
            'nama' => 'superadmin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
            'description' => 'untuk master admin',
        ]);

        $user->attachRole($role);

        $permissions = ['permission', 'role', 'umum', 'user', 'peminjaman', 'pengembalian', 'buku', 'kategori', 'kelas', 'siswa', 'guru'];
        foreach ($permissions as $item) {
            Permission::create([
                'grup' => $item,
                'name' => $item . '-create',
            ]);

            Permission::create([
                'grup' => $item,
                'name' => $item . '-read',
            ]);

            Permission::create([
                'grup' => $item,
                'name' => $item . '-update',
            ]);

            Permission::create([
                'grup' => $item,
                'name' => $item . '-delete',
            ]);

            Permission::create([
                'grup' => $item,
                'name' => $item . '-print',
            ]);
        }

        $permissionID = [];
        $getPermissions = Permission::all();
        foreach ($getPermissions as $row) {
            $permissionID[] = $row->id;
        }

        $role->attachPermissions($permissionID);

        Umum::create([
            'nama' => env('APP_NAME'),
            'timezone' => 'Asia/Jakarta'
        ]);
    }
}
