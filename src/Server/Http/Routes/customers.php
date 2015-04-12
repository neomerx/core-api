<?php

use \Neomerx\CoreApi\Server\Http\Controllers\CustomersControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\CustomerRisksControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\CustomerTypesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\CustomerAddressesControllerJson;

Route::resource(
    '/customers',
    CustomersControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::get(
    '/customers/{id_customer}/addresses',
    ['uses'  => CustomerAddressesControllerJson::class.'@index']
);
Route::post(
    '/customers/{id_customer}/addresses/{id_address}',
    ['uses' => CustomerAddressesControllerJson::class.'@store']
);
Route::delete(
    '/customers/{id_customer}/addresses/{id_address}/{type}',
    ['uses' => CustomerAddressesControllerJson::class.'@destroy']
);
Route::put(
    '/customers/{id_customer}/addresses/{id_address}/{type}/default',
    ['uses' => CustomerAddressesControllerJson::class.'@setDefault']
);

Route::resource(
    '/customer-types',
    CustomerTypesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/customer-risks',
    CustomerRisksControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
