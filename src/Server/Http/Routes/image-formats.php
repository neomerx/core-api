<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\ImageFormatsControllerJson;

Route::resource(
    '/image-formats',
    ImageFormatsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
