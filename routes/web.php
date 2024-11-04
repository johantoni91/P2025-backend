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
    $router->get('layout', 'LayoutController@index'); // Data layout
    $router->post('register', 'UsersController@register'); // Tambah user
    $router->post('login', 'AuthController@login'); // Login

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('logout', 'AuthController@logout');

        // Edit Layout
        $router->post('layout/update/{id}', 'LayoutController@update');

        // Dashboard
        $router->get('dashboard', 'DashboardController@index');

        // Users
        $router->get('users', 'UsersController@index'); // Dapat User per 5 record
        $router->get('users/all', 'UsersController@indexAll'); // Dapat semua users
        $router->get('users/search', 'UsersController@search'); // Dapat semua users yang dicari
        // $router->post('users/register', 'UsersController@register'); // Tambah user
        $router->post('users/update/{id}', 'UsersController@update'); // Ubah user
        $router->get('users/destroy/{id}', 'UsersController@destroy'); // Hapus user

        $router->get('log', 'LogController@index'); // Dapat log per 5 record
        $router->get('log/all', 'LogController@all'); // Dapat semua log
        $router->get('log/search', 'LogController@search'); // Dapat semua log yang dicari
        $router->post('log/store', 'LogController@store'); // Menambah log

        // Roles
        $router->get('role', 'RoleController@index'); // Dapat semua Roles
        $router->post('role/store', 'RoleController@store'); // Tambah Role
        $router->post('role/update/{id}', 'RoleController@update'); // Ubah Role
        // $router->get('role/destroy/{id}', 'RoleController@destroyRole'); // Hapus Role
    });
});
