<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\AddressesControllerJson;

Route::resource(
    '/addresses',
    AddressesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
