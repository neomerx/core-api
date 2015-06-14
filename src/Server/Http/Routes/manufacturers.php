<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\ManufacturersControllerJson;

Route::resource(
    '/manufacturers',
    ManufacturersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
