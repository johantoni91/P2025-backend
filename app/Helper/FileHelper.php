<?php

namespace App\Helper;

use Illuminate\Support\Facades\File;

class FileHelper
{
    public static function publicPath($fullUrl): string
    {
        $path = parse_url($fullUrl, PHP_URL_PATH);
        $relativePath = ltrim($path, '/');
        return $relativePath;
    }

    /*
    * The variable "checkClientHasFile" default return is true, if false that function isn't working
    * sequelFileName is for name file between random code and extension file
    * Variable "reqFile" is request from client that client was sending file by API
    * fullUrl is default null if has file at database
    * Variable "folder" is used for named folder to save the file
    */

    public static function insertOrUpdateFile($checkClientHasFile, $sequelFileName, $reqFile, $fullUrl = null, $folder)
    {
        if ($checkClientHasFile) {
            $filename = rand() . $sequelFileName . $reqFile->getClientOriginalExtension();
            $fullUrl == null ? false : self::deleteFile($fullUrl);
            $reqFile->move($folder, $filename);
            return env('APP_URL', 'http://localhost:8001') . '/' . 'layout/' . $filename;
        } else {
            return false;
        }
    }

    public static function deleteFile($fullUrl): bool
    {
        $path = parse_url($fullUrl, PHP_URL_PATH);
        $relativePath = ltrim($path, '/');
        return File::exists($relativePath) ? File::delete($relativePath) : false;
    }
}
