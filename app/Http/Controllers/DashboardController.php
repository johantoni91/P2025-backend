<?php

namespace App\Http\Controllers;

use App\Helper\API;
use App\Models\LogActivity;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function index()
    {
        $aksi = 'Dapat Semua Data';
        try {
            $output = [
                'users' => User::count(),
                'logs'  => LogActivity::count(),
                'role'  => Role::count(),
                'graph_user' => User::select(
                    DB::raw('DATE(created_at) AS date'),
                    DB::raw('COUNT(*) AS count')
                )
                    ->where('created_at', '<=', DB::raw('DATE_ADD(NOW(), INTERVAL 1 DAY)'))
                    ->groupBy(
                        DB::raw('DATE(created_at)')
                    )
                    ->orderBy(
                        DB::raw('DATE(created_at)')
                    )
                    ->get(),
                'graph_log' => LogActivity::select(
                    DB::raw('DATE(updated_at) AS date'),
                    DB::raw('COUNT(*) AS count')
                )
                    ->where('updated_at', '<=', DB::raw('DATE_ADD(NOW(), INTERVAL 1 DAY)'))
                    ->groupBy(
                        DB::raw('DATE(updated_at)')
                    )
                    ->orderBy(
                        DB::raw('DATE(updated_at)')
                    )
                    ->get(),
                'graph_role' => Role::select(
                    DB::raw('DATE(created_at) AS date'),
                    DB::raw('COUNT(*) AS count')
                )
                    ->where('created_at', '<=', DB::raw('DATE_ADD(NOW(), INTERVAL 1 DAY)'))
                    ->groupBy(
                        DB::raw('DATE(created_at)')
                    )
                    ->orderBy(
                        DB::raw('DATE(created_at)')
                    )
                    ->get()
            ];
            return API::withData(true, $aksi, $output);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }
}
