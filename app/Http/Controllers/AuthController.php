<?php

namespace App\Http\Controllers;

use App\Helper\API;
use App\Models\Access;
use App\Models\Login;
use App\Models\Modul;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function login(Request $req)
    {
        $aksi = 'Masuk';
        $this->validate($req, [
            'email'  => 'required',
            'password'  => 'required',
        ]);

        try {
            $user = User::where('email', $req->email)->first();
            if (!($user && Hash::check($req->password, $user->password))) {
                return API::withoutData(false, $aksi . ' Password salah', 400);
            } else {
                $role_check = Role::where('id', $user->role)->first();
                if ($role_check->status == '0') {
                    return API::withoutData(false, 'Status role anda telah dinonaktifkan, silahkan konfirmasi ke atasan anda', 400);
                } else {
                    if (
                        !($user->remember_token) || $user->remember_token == null ||
                        $user->remember_token == ''
                    ) {
                        User::where('email', $req->email)->update([
                            'remember_token' => Crypt::encrypt(rand())
                        ]);
                    }
                    $user_al = User::where('email', $req->email)->first();
                    $data = [
                        'user'    => $user_al,
                        'role'    => Access::with(['module', 'role'])->where('roles_id', $user_al->role)->get()
                    ];
                    return API::withData(true, $aksi, $data);
                }
            }
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function logout(Request $req)
    {
        $aksi = 'Logout';
        try {
            $user = User::where('remember_token', $req->bearerToken())->first();
            if (!$user) {
                return API::withoutData(false, 'Akun tidak ditemukan', 400);
            }
            User::where('remember_token', $req->bearerToken())->update([
                'remember_token'    => ''
            ]);
            return API::withoutData(true, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $th->getMessage(), 400);
        }
    }
}
