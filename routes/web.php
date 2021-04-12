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

$router->group(['prefix' => '{id}'], function() use ($router) {
	$router->get('csv', 'GeneralController@csv');
	$router->get('nl', 'GeneralController@nl');
	$router->get('json_array', 'GeneralController@json_array');

    $router->get('minecraft_csv', 'GeneralController@minecraft_csv');
    $router->get('minecraft_nl', 'GeneralController@minecraft_nl');
    $router->get('minecraft_json_array', 'GeneralController@minecraft_json_array');
    $router->get('minecraft_uuid_csv', 'GeneralController@minecraft_uuid_csv');
    $router->get('minecraft_uuid_nl', 'GeneralController@minecraft_uuid_nl');
    $router->get('minecraft_uuid_json_array', 'GeneralController@minecraft_uuid_json_array');
    $router->get('minecraft_whitelist', 'GeneralController@minecraft_whitelist');

    $router->get('steam_csv', 'GeneralController@steam_csv');
    $router->get('steam_nl', 'GeneralController@steam_nl');
    $router->get('steam_json_array', 'GeneralController@steam_json_array');

    $router->get('[{path:.*}]', function () {
        return response(['message' => 'Invalid endpoint'], 404);
	});
});

$router->get('[{path:.*}]', function () {
    return response()->json(['message' => 'Not Found'], 404);
});
