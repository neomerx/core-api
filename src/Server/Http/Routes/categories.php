<?php

use \Neomerx\CoreApi\Server\Http\Controllers\CategoriesControllerJson;

Route::get('/categories/{code}/descendants', ['uses' => CategoriesControllerJson::class.'@descendants']);
Route::put('/categories/{code}/up', ['uses' => CategoriesControllerJson::class.'@moveUp']);
Route::put('/categories/{code}/down', ['uses' => CategoriesControllerJson::class.'@moveDown']);
Route::put('/categories/{code}/move-to/{to}', ['uses' => CategoriesControllerJson::class.'@attach']);
Route::get('/categories/{code}/products', ['uses' => CategoriesControllerJson::class.'@showProducts']);
Route::put('/categories/{code}/product-positions', ['uses' => CategoriesControllerJson::class.'@updatePositions']);
Route::get('/categories', ['uses' => CategoriesControllerJson::class.'@top']);
Route::resource('/categories', CategoriesControllerJson::class, ['only' => ['show', 'store', 'update', 'destroy']]);
