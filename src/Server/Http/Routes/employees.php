<?php

use \Neomerx\CoreApi\Server\Http\Controllers\JsonApi\EmployeesControllerJson;

Route::resource(
    '/employees',
    EmployeesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
