<?php

namespace App\Helper;

class API
{
    public static function withoutData($status, $message, $statusCode = 200, $error = null)
    {
        return response()->json([
            'status'    => $status,
            'message'   => $status == true ? 'Berhasil ' . $message : 'Gagal ' . $message,
            'error'     => $error
        ], $statusCode);
    }

    public static function withData($status, $message, $data, $statusCode = 200)
    {
        return response()->json([
            'status'    => $status,
            'message'   => $status == true ? 'Berhasil ' . $message : 'Gagal ' . $message,
            'data'      => $data
        ], $statusCode);
    }
}
