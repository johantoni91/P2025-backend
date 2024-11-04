<?php

namespace App\Http\Controllers;

use App\Helper\API;
use app\Models\LogActivity;
use App\Models\Role;
use App\Models\User;

class DashboardController extends Controller
{
    function index()
    {
        $aksi = 'Dapat Semua Data';
        $output = [
            'users' => User::count(),
            'logs'  => LogActivity::count(),
            'role'  => Role::count()
        ];
        return API::withData(true, $aksi, $output);
    }
}
