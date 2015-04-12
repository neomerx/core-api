<?php

use \Neomerx\CoreApi\Server\Http\Controllers\RegionsControllerJson;

Route::resource(
    '/regions',
    RegionsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
