<?php

namespace App\Http\Controllers;

use App\Helper\API;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    function index()
    {
        $aksi = 'Mendapatkan Semua Users';
        $users = User::orderBy('name')->paginate(5);
        return API::withData(true, $aksi, $users);
    }

    function indexAll()
    {
        $aksi = 'Mendapatkan Semua Users';
        $users = User::orderBy('name', 'desc')->select('name', 'email', 'role', 'created_at')->get();
        return API::withData(true, $aksi, $users);
    }

    function search(Request $req)
    {
        $aksi = 'Mendapatkan hasil pencarian user';
        try {
            $user = User::orderBy('created_at', 'desc')
                ->where('name', 'LIKE', '%' . $req->name . '%')
                ->where('role', $req->role)
                ->where('email', 'LIKE', '%' .  $req->email . '%')
                ->paginate(5)->appends([
                    'name'  => $req->name,
                    'role'  => $req->role,
                    'email' => $req->email
                ]);
            return API::withData(true, $aksi, $user);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function register(Request $req)
    {
        $aksi = 'Tambah User';

        try {
            $this->validate($req, [
                'name'  => 'required',
                'role'  => 'required',
                'email'  => 'required|email:rfc',
                'password'  => 'required',
            ]);

            $role = Role::where('id', $req->role)->first();
            $check = User::where('name', $req->name)->orWhere('email', $req->email)->first();
            if ($check) {
                return API::withoutData(false, $aksi, 400, 'User sudah terdaftar');
            }
            $param = [
                'id'         => rand(),
                'name'       => $req->name,
                'email'      => $req->email,
                'role'       => $role->id,
                'password'   => Hash::make($req->password)
            ];

            if ($req->hasFile('photo')) {
                $filename = rand() . '_' . strtolower(str_replace(" ", "_", $req->name)) . '.' . $req->file('photo')->getClientOriginalExtension();
                $req->file('photo')->move('user', $filename);
                $param['photo'] = $filename;
            }

            $user = User::insert($param);
            if (!$user) {
                return API::withoutData(false, $aksi, 400);
            }
            return API::withoutData(true, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        $aksi = 'Ubah User';
        $this->validate($req, [
            'name'   => 'required',
            'role'   => 'required',
            'email'  => 'required|email:rfc',
        ]);

        try {
            $user = User::find($id);
            if (!$user) {
                return API::withoutData(false, $aksi, 400, 'User Tidak Ada');
            }

            $param = [
                'name'      => $req->name,
                'email'     => $req->email,
                'role'      => $req->role,
                'password'  => $req->password ? Hash::make($req->password) : $user->password,
            ];

            if ($req->hasFile('photo')) {
                $filename = rand() . '_' . strtolower(str_replace(" ", "_", $req->name)) . '.' . $req->file('photo')->getClientOriginalExtension();
                File::exists('user/' . $user->photo) ? File::delete('user/' . $user->photo) : '';
                $req->file('photo')->move('user', $filename);
                $param['photo'] = $filename;
            }

            $user->update($param);
            return API::withData(true, $aksi, User::where('id', $id)->first());
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function destroy($id)
    {
        try {
            $aksi = 'Hapus User';
            $user = User::find($id);
            if (!$user) {
                return API::withoutData(false, $aksi, 400, 'User Tidak Ada');
            }
            File::exists('user/' . $user->photo) ? File::delete('user/' . $user->photo) : '';
            $user->delete();
            return API::withoutData(true, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }
}
