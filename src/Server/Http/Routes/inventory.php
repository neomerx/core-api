<?php

use \Neomerx\CoreApi\Server\Http\Controllers\StoresControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\InventoryControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\WarehousesControllerJson;

Route::get('/inventory/{warehouse}/{sku}', ['uses' => InventoryControllerJson::class.'@show']);
Route::put('/inventory/{warehouse}/{sku}', ['uses' => InventoryControllerJson::class.'@increment']);
Route::delete('/inventory/{warehouse}/{sku}', ['uses' => InventoryControllerJson::class.'@decrement']);
Route::put('/inventory/{warehouse}/{sku}/reserve', ['uses' => InventoryControllerJson::class.'@makeReserve']);
Route::delete('/inventory/{warehouse}/{sku}/reserve', ['uses' => InventoryControllerJson::class.'@releaseReserve']);

Route::resource(
    '/warehouse',
    WarehousesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/store',
    StoresControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
