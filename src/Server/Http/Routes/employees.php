<?php

use \Neomerx\CoreApi\Server\Http\Controllers\EmployeesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\EmployeeRolesControllerJson;

Route::resource(
    '/employees',
    EmployeesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/employees/{employeeId}/roles',
    EmployeeRolesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'destroy']]
);
