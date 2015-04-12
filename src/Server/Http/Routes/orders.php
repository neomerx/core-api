<?php

use \Neomerx\CoreApi\Server\Http\Controllers\OrdersControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\OrderStatusesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\ShippingOrdersControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\ShippingStatusesControllerJson;

Route::resource(
    '/shipping-orders',
    ShippingOrdersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/shipping-statuses',
    ShippingStatusesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::post(
    '/order-statuses/{codeFrom}/available/{codeTo}',
    ['uses' => OrderStatusesControllerJson::class.'@storeAvailable']
);
Route::delete(
    '/order-statuses/{codeFrom}/available/{codeTo}',
    ['uses' => OrderStatusesControllerJson::class.'@destroyAvailable']
);
Route::resource(
    '/order-statuses',
    OrderStatusesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/orders',
    OrdersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
