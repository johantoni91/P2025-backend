<?php

namespace App\Http\Controllers;

use App\Helper\API;
use app\Models\LogActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
    function index()
    {
        $aksi = 'Mendapatkan Semua Log Aktivitas';
        $log = LogActivity::orderBy('created_at', 'desc')->paginate(5);
        return API::withData(true, $aksi, $log, 200);
    }

    function all()
    {
        $aksi = 'Mendapatkan Semua Log Aktivitas';
        $log = LogActivity::orderBy('created_at', 'desc')->get();
        return API::withData(true, $aksi, $log, 200);
    }

    function store(Request $request)
    {
        try {
            $aksi = 'Menambah Log';
            $store = LogActivity::insert([
                'username'      => $request->username,
                'action'        => $request->action,
                'entity'        => $request->entity,
                'entity_id'     => $request->entity_id,
                'ip_address'    => $request->ip_address,
                'user_agent'    => $request->user_agent,
                'url'           => $request->url,
                'status_code'   => $request->status_code,
                'location'      => $request->location,
                'message'       => $request->message,
                'additional'    => json_encode([
                    'Robot'         => $request->Robot,
                    'Device'        => $request->Device,
                    'Browser'       => $request->Browser,
                    'Referer'       => $request->Referer,
                    'Language'      => $request->Language,
                    'Authorization' => $request->Authorization,
                    'Port'          => $request->Port,
                    'Content-Type'  => $request->content_type
                ]),
                'created_at'    => $request->created_at ?? Carbon::now()
            ]);
            if (!$store) {
                return API::withoutData(false, $aksi, 400);
            }
            return API::withoutData(true, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function search(Request $req)
    {
        $aksi = 'Mendapatkan hasil pencarian log';
        try {
            if ($req->start == null || $req->end == null) {
                $log = LogActivity::orderBy('created_at', 'desc')
                    ->where('username', 'LIKE', '%' . $req->username . '%')
                    ->where('action', 'LIKE', '%' . $req->action . '%')
                    ->where('ip_address', 'LIKE', '%' . $req->ip_address . '%')
                    ->paginate(5)->appends([
                        'username'   => $req->username,
                        'action'     => $req->action,
                        'ip_address' => $req->ip_address,
                        'start'      => $req->start,
                        'end'        => $req->end
                    ]);
            } else {
                $log = LogActivity::orderBy('created_at', 'desc')
                    ->where('username', 'LIKE', '%' . $req->username . '%')
                    ->where('action', 'LIKE', '%' . $req->action . '%')
                    ->where('ip_address', 'LIKE', '%' . $req->ip_address . '%')
                    ->whereBetween('created_at', [$req->start, $req->end])
                    ->paginate(5)->appends([
                        'username'   => $req->username,
                        'action'     => $req->action,
                        'ip_address' => $req->ip_address,
                        'start'      => $req->start,
                        'end'        => $req->end
                    ]);
            }
            return API::withData(true, $aksi, $log);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }
}
