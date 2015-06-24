<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\RolesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\ObjectTypesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\RoleObjectsControllerJson;

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
    '/role-objects',
    RoleObjectsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
