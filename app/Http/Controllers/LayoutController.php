<?php

namespace App\Http\Controllers;

use App\Helper\API;
use App\Helper\ExtractUrl;
use App\Helper\FileHelper;
use App\Models\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LayoutController extends Controller
{
    function index()
    {
        $aksi = 'mendapatkan data layout';
        return API::withData(true, $aksi, Layout::get());
    }

    function update(Request $req, $id)
    {
        $aksi = 'mengubah layout';
        try {
            $layout = Layout::find($id);
            if (!$layout) {
                return API::withoutData(false, $aksi, 400, 'Data layout tidak ditemukan');
            }

            $param = [
                'app_name'       => $req->app_name,
                'short_app_name' => $req->short_app_name,
                'header'         => $req->header,
                'footer'         => $req->footer,
                'fullscreen'     => $req->fullscreen,
                'login_position' => $req->login_position
            ];

            if ($req->hasFile('icon')) {
                $filename = rand() . '_app_icon.' . $req->file('icon')->getClientOriginalExtension();
                File::exists(FileHelper::publicPath($layout->icon)) ? File::delete(FileHelper::publicPath($layout->icon)) : '';
                $req->file('icon')->move('layout', $filename);
                $param['icon'] = env('APP_URL', 'http://localhost:8001') . '/' . 'layout/' . $filename;
            }

            if ($req->hasFile('img_login_bg')) {
                $filename = rand() . '_img_login_bg.' . $req->file('img_login_bg')->getClientOriginalExtension();
                File::exists(FileHelper::publicPath($layout->img_login_bg)) ? File::delete(FileHelper::publicPath($layout->img_login_bg)) : '';
                $req->file('img_login_bg')->move('layout', $filename);
                $param['img_login_bg'] = env('APP_URL', 'http://localhost:8001') . '/' . 'layout/' . $filename;
            }

            $res = $layout->update($param);
            if ($res) {
                return API::withData(true, $aksi, Layout::get());
            } else {
                return API::withoutData(false, $aksi, 400);
            }
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }
}
