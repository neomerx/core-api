<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\CarriersControllerJson;

Route::get('/calculators', ['uses' => CarriersControllerJson::class.'@showCalculators']);
Route::get('/calculators/{code}', ['uses' => CarriersControllerJson::class.'@showCalculator']);

Route::resource(
    '/carriers',
    CarriersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
