<?php

use \Neomerx\CoreApi\Server\Http\Controllers\MeasurementsControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\FeatureValuesControllerJson;
use \Neomerx\CoreApi\Server\Http\Controllers\CharacteristicsControllerJson;

Route::resource(
    '/feature/measurements',
    MeasurementsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);

Route::resource(
    '/feature/characteristics',
    CharacteristicsControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
Route::get(
    '/feature/characteristics/{code}/values',
    ['uses' => CharacteristicsControllerJson::class.'@getValues']
);

Route::resource(
    '/feature/values',
    FeatureValuesControllerJson::class,
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
);
