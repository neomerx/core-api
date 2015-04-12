<?php

use \Neomerx\CoreApi\Server\Http\Controllers\TaxesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\TaxRulesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\TaxRulePostcodesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\TaxRuleTerritoriesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\TaxRuleProductTypesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\TaxRuleCustomerTypesControllerJson;

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

Route::resource(
    '/tax-rules/{ruleId}/territories',
    TaxRuleTerritoriesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/tax-rules/{ruleId}/postcodes',
    TaxRulePostcodesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/tax-rules/{ruleId}/customer-types',
    TaxRuleCustomerTypesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/tax-rules/{ruleId}/product-types',
    TaxRuleProductTypesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
