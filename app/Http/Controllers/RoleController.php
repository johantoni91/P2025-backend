<?php

namespace App\Http\Controllers;

use App\Helper\API;
use App\Models\Access;
use App\Models\Modul;
use app\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index()
    {
        $aksi = 'Mendapatkan data role';
        try {
            $data = [
                'modul'         => Modul::all(),
                'role'          => Role::orderBy('name', 'desc')->get(),
                'access'        => Access::with(['role', 'module'])->get()
            ];
            return API::withData(true, $aksi, $data);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function store(Request $req)
    {
        $aksi = 'Menambahkan Role';
        try {
            $insert_role = Role::insert([
                'id'    => rand(),
                'name'  => $req->role_name,
            ]);
            if ($insert_role) {
                $getRole = Role::where('name', $req->role_name)->first();
            }
            $modules = Modul::get();
            foreach ($modules as $mod) {
                Access::insert([
                    'id'         => rand(),
                    'roles_id'   => $getRole->id,
                    'modules_id' => $mod->id
                ]);
            }
            return API::withoutData(true, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        $aksi = 'Ubah Role';
        try {
            $update = Access::where('roles_id', $id)->where('modules_id', $req->modules_id)->update([
                'status'    => $req->status,
                'dashboard' => $req->dashboard,
                'create'    => $req->permission == null ? '0' : (in_array('create', $req->permission) ? '1' : '0'),
                'update'    => $req->permission == null ? '0' : (in_array('update', $req->permission) ? '1' : '0'),
                'delete'    => $req->permission == null ? '0' : (in_array('delete', $req->permission) ? '1' : '0')
            ]);
            if (!$update) {
                return API::withoutData(false, $aksi, 400);
            }
            return API::withoutData(true, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function destroy($id)
    {
        $aksi = 'Hapus Role';
        $role = Role::find($id);
        if (!$role) {
            return API::withoutData(false, $aksi, 400);
        }
        $role->delete();
        return API::withoutData(true, $aksi);
    }
}
