<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\ImagesControllerJson;

Route::resource(
    '/images',
    ImagesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
