<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\SuppliersControllerJson;

Route::resource(
    '/suppliers',
    SuppliersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
