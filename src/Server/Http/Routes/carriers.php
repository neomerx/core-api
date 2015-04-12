<?php

use \Neomerx\CoreApi\Server\Http\Controllers\CarriersControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\CarrierPostcodesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\CarrierTerritoriesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\CarrierCustomerTypesControllerJson;

Route::get('/tariff-calculators', ['uses' => CarriersControllerJson::class.'@showCalculators']);

Route::resource(
    '/carriers',
    CarriersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/carrier-customer-types',
    CarrierCustomerTypesControllerJson::class,
    ['only' => ['show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/carrier-postcodes',
    CarrierPostcodesControllerJson::class,
    ['only' => ['show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/carrier-territories',
    CarrierTerritoriesControllerJson::class,
    ['only' => ['show', 'store', 'update', 'destroy']]
);
