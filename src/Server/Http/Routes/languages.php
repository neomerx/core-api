<?php

use \Neomerx\CoreApi\Server\Http\Controllers\LanguagesControllerJson;

Route::resource(
    '/languages',
    LanguagesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
