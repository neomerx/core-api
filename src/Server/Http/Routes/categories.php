<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\CategoriesControllerJson;

Route::resource(
    '/categories',
    CategoriesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::patch('/categories/{code}/up', ['uses' => CategoriesControllerJson::class.'@moveUp']);
Route::patch('/categories/{code}/down', ['uses' => CategoriesControllerJson::class.'@moveDown']);
