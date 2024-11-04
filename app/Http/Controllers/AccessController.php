<?php

namespace App\Http\Controllers;

use App\Helper\API;
use App\Models\Access;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    function index()
    {
        $aksi = 'Mendapatkan semua akses';
        $akses = Access::all();
        return API::withData(true, $aksi, $akses);
    }
}
