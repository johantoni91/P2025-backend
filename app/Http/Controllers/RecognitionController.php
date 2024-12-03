<?php

namespace App\Http\Controllers;

use App\Helper\API;
use App\Models\Recognition;
use Illuminate\Http\Request;

class RecognitionController extends Controller
{
    function index()
    {
        $aksi = 'Mendapatkan data recognition';
        $recog = Recognition::all();
        return API::withData(true, $aksi, $recog);
    }

    function store(Request $req)
    {
        $aksi = 'Menyimpan data recognition';
        try {
            $param = [
                'mask'       => $req->mask,
                // 'gender'     => $req->gender,
                'similarity' => $req->similarity
            ];
            $recog = Recognition::insert($param);
            if ($recog) {
                return API::withoutData(true, $aksi);
            }
            return API::withoutData(false, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function update(Request $req, $id)
    {
        $aksi = 'Mengubah data recognition';
        try {
            $param = [
                'mask'       => $req->mask,
                // 'gender'     => $req->gender,
                'similarity' => $req->similarity
            ];
            $recog = Recognition::find($id);
            if ($recog) {
                $recog->update($param);
                return API::withData(true, $aksi, $recog);
            }
            return API::withoutData(false, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }

    function destroy($id)
    {
        $aksi = 'Menghapus data recognition';
        try {
            $recog = Recognition::find($id);
            if (!$recog) {
                return API::withoutData(false, $aksi);
            }
            $recog->delete;
            return API::withoutData(true, $aksi);
        } catch (\Throwable $th) {
            return API::withoutData(false, $aksi, 400, $th->getMessage());
        }
    }
}
