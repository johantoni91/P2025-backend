<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json(['Backend' => 'Running'], 200);
});


$router->group(['prefix' => 'api/v1/'], function () use ($router) {
    $router->get('recognition', 'RecognitionController@index'); // Data recognition
    $router->get('layout', 'LayoutController@index'); // Data layout
    $router->post('register', 'UsersController@register'); // Tambah user
    $router->get('log-in/id', 'AuthController@id'); // Face Recognition
    $router->post('login', 'AuthController@login'); // Login
    $router->post('login-face', 'AuthController@loginWithFace'); // Login Face Recognition
    $router->post('login-token', 'AuthController@loginWithToken'); // Login Access Token

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('logout', 'AuthController@logout');
        $router->post('layout/update/{id}', 'LayoutController@update'); // Edit Layout
        $router->get('dashboard', 'DashboardController@index'); // Dashboard

        // Recognition
        $router->post('recognition/store', 'RecognitionController@store');
        $router->post('recognition/update/{id}', 'RecognitionController@update');
        $router->get('recognition/destroy/{id}', 'RecognitionController@destroy');

        // Users
        $router->get('users', 'UsersController@index'); // Dapat User per 5 record
        $router->group(['prefix' => 'users/'], function () use ($router) {
            $router->get('all', 'UsersController@indexAll'); // Dapat semua users
            $router->get('find/{id}', 'UsersController@find'); // Dapatkan user berdasarkan id
            $router->get('photo-recog/{id}', 'UsersController@checkPhotoRecognition'); // Dapatkan foto recognition user berdasarkan id
            $router->get('search', 'UsersController@search'); // Dapat semua users yang dicari
            // $router->post('register', 'UsersController@register'); // Tambah user
            $router->post('update/{id}', 'UsersController@update'); // Ubah user
            $router->get('destroy/{id}', 'UsersController@destroy'); // Hapus user
            $router->get('token/{id}', 'UsersController@getToken'); // Token user
        });

        // Log
        $router->get('log', 'LogController@index'); // Dapat log per 5 record
        $router->group(['prefix' => 'log/'], function () use ($router) {
            $router->get('all', 'LogController@all'); // Dapat semua log
            $router->get('search', 'LogController@search'); // Dapat semua log yang dicari
            $router->post('store', 'LogController@store'); // Menambah log
        });

        // Roles
        $router->get('role', 'RoleController@index'); // Dapat semua Roles
        $router->group(['prefix' => 'role/'], function () use ($router) {
            $router->post('store', 'RoleController@store'); // Tambah Role
            $router->post('update/{id}', 'RoleController@update'); // Ubah Role
            $router->get('setting-face/{id}', 'RoleController@face'); // Ubah Role
            // $router->get('destroy/{id}', 'RoleController@destroyRole'); // Hapus Role
        });
    });
});
