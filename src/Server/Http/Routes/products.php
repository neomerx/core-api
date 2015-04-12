<?php

use \Neomerx\CoreApi\Server\Http\Controllers\ProductsControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\VariantsControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\ProductImagesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\ProductRelatedControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\SpecificationsControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\ProductTaxTypesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\ProductCategoriesControllerJson;

Route::resource(
    '/products',
    ProductsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/variants',
    VariantsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/product-tax-types',
    ProductTaxTypesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/related',
    ProductRelatedControllerJson::class,
    ['only' => ['show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/product-categories',
    ProductCategoriesControllerJson::class,
    ['only' => ['show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/specifications',
    SpecificationsControllerJson::class,
    ['only' => ['show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/product-images',
    ProductImagesControllerJson::class,
    ['only' => ['show', 'store', 'update', 'destroy']]
);
