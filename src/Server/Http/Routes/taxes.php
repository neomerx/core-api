<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\TaxesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\TaxRulesControllerJson;

Route::resource(
    '/taxes',
    TaxesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/tax-rules',
    TaxRulesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
