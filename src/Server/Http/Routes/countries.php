<?php

use \Neomerx\CoreApi\Server\Http\Controllers\CountriesControllerJson;

Route::get('/countries/{code}/regions', ['uses' => CountriesControllerJson::class.'@regions']);

Route::resource(
    '/countries',
    CountriesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
