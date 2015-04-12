<?php

use \Neomerx\CoreApi\Server\Http\Controllers\ImageFormatsControllerJson;

Route::resource(
    '/image-formats',
    ImageFormatsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
