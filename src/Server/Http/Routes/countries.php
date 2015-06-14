<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\CountriesControllerJson;

Route::resource(
    '/countries',
    CountriesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
