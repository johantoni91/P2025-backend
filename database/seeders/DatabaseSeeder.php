<?php

namespace Database\Seeders;

use App\Models\Access;
use App\Models\Layout;
use App\Models\Modul;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $role = ['superadmin', 'admin', 'pegawai'];
        $modul = ['Dashboard', 'Management Users', 'Log Aktivitas', 'Role', 'Pengaturan Tampilan'];
        $route = ['dashboard', 'users', 'log', 'role', 'layout'];
        for ($i = 0; $i < count($modul); $i++) {
            Modul::insert(['id' => rand(), 'name' => $modul[$i], 'route' => $route[$i]]);
        }

        foreach ($role as $r) {
            Role::insert([
                'id'    => rand(),
                'name'  => $r
            ]);
        }

        $modules = Modul::all();
        $roles = Role::all();

        foreach ($modules as $mod) {
            foreach ($roles as $role) {
                Access::insert([
                    'id'            => rand(),
                    'roles_id'      => $role->id,
                    'modules_id'    => $mod->id
                ]);
            }
        }

        $superadmin = Role::where('name', 'superadmin')->first();
        User::insert([
            'id'        => rand(),
            'name'      => 'Johan Toni Wijaya',
            'role'      => $superadmin->id,
            'photo'     => null,
            'email'     => 'jo@gmail.com',
            'password'  => Hash::make('123')
        ]);

        Layout::insert([
            'id'             => rand(),
            'app_name'       => 'APP',
            'short_app_name' => 'APP',
            'header'         => '1',
            'footer'         => '1'
        ]);
    }
}
