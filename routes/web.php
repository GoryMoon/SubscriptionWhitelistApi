<?php

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

	$router->get('[{path:.*}]', function () {
                 return response(['message' => 'Invalid endpoint'], 404);
	});
});

$router->get('[{path:.*}]', function () {
    return response()->json(['message' => 'Not Found'], 404);
});
