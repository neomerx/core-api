<?php

use \Neomerx\CoreApi\Server\Http\Controllers\RolesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\ObjectTypesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\RoleObjectTypesControllerJson;

Route::resource(
    '/object-types',
    ObjectTypesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'destroy']]
);

Route::resource(
    '/roles',
    RolesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/roles/{roleCode}/object-types',
    RoleObjectTypesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'destroy']]
);
