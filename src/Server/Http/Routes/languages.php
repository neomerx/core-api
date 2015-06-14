<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\LanguagesControllerJson;

Route::resource(
    '/languages',
    LanguagesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
