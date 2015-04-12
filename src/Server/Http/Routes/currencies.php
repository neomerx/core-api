<?php

use \Neomerx\CoreApi\Server\Http\Controllers\CurrenciesControllerJson;

Route::resource(
    '/currencies',
    CurrenciesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
