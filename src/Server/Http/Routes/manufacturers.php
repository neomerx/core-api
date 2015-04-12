<?php

use \Neomerx\CoreApi\Server\Http\Controllers\ManufacturersControllerJson;

Route::resource(
    '/manufacturers',
    ManufacturersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
