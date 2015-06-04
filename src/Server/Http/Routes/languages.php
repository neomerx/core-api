<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\LanguagesControllerJson;

Route::get('/languages', ['uses' => LanguagesControllerJson::class.'@index']);
Route::post('/languages', ['uses' => LanguagesControllerJson::class.'@store']);
Route::get('/languages/{id}', ['uses' => LanguagesControllerJson::class.'@show']);
Route::patch('/languages/{id}', ['uses' => LanguagesControllerJson::class.'@update']);
Route::delete('/languages/{id}', ['uses' => LanguagesControllerJson::class.'@destroy']);
