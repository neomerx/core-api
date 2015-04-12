<?php

use \Neomerx\CoreApi\Server\Http\Controllers\SupplyOrdersControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\SupplyOrderDetailsControllerJson;

Route::resource(
    '/supply-orders',
    SupplyOrdersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/supply-orders/{supplyOrderId}/details',
    SupplyOrderDetailsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
