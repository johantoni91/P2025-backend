<?php

namespace App\Http\Middleware;

use app\Models\LogActivity;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Response;
use Jenssegers\Agent\Agent;

class Auth
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        $response = new Response();
        $agent = new Agent();
        $user = User::where('remember_token', $token)->first();
        try {
            if ($user) {
                return $next($request);
            } else {
                LogActivity::insert([
                    'username'      => 'Unknown',
                    'action'        => 'Akses API',
                    'entity'        => 'Entitas Tak Dikenal',
                    'entity_id'     => '-',
                    'ip_address'    => $request->ip_address ?? $request->ip(),
                    'user_agent'    => $request->user_agent ?? $request->header('User-Agent'),
                    'url'           => $request->url ?? $request->fullUrl(),
                    'status_code'   => $response->status(),
                    'location'      => '',
                    'message'       => 'TERJADI PENYUSUPAN OLEH ENTITAS TAK DIKENAL MENCOBA AKSES API',
                    'additional'    => json_encode([
                        'Robot'         => $request->Robot ?? $agent->isRobot(),
                        'Device'        => $request->Device ?? $agent->device(),
                        'Browser'       => $request->Browser ?? $agent->browser(),
                        'Referer'       => $request->Referer ?? $request->header('referer'),
                        'Language'      => $request->Language ?? $request->header('Accept-Language'),
                        'Authorization' => $request->Authorization ?? $request->header('Authorization'),
                        'Port'          => $request->Port ?? $request->getPort(),
                        'Content-Type'  => $request->content_type ?? $request->header('Content-Type')
                    ]),
                    'created_at'    => Carbon::now()
                ]);
                return response()->json([
                    'username'      => 'Unknown',
                    'action'        => 'Akses API',
                    'entity'        => 'Entitas Tak Dikenal',
                    'entity_id'     => '-',
                    'ip_address'    => $request->ip_address ?? $request->ip(),
                    'user_agent'    => $request->user_agent ?? $request->header('User-Agent'),
                    'url'           => $request->url ?? $request->fullUrl(),
                    'status_code'   => $response->status(),
                    'location'      => '',
                    'message'       => 'TERJADI PENYUSUPAN OLEH ENTITAS TAK DIKENAL MENCOBA AKSES API',
                    'additional'    => json_encode([
                        'Robot'         => $request->Robot ?? $agent->isRobot(),
                        'Device'        => $request->Device ?? $agent->device(),
                        'Browser'       => $request->Browser ?? $agent->browser(),
                        'Referer'       => $request->Referer ?? $request->header('referer'),
                        'Language'      => $request->Language ?? $request->header('Accept-Language'),
                        'Authorization' => $request->Authorization ?? $request->header('Authorization'),
                        'Port'          => $request->Port ?? $request->getPort(),
                        'Content-Type'  => $request->content_type ?? $request->header('Content-Type')
                    ]),
                    'created_at'    => Carbon::now()
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage(),
            ], 500);
        }
    }
}
