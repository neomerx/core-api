<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\RegionsControllerJson;

Route::resource(
    '/regions',
    RegionsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
