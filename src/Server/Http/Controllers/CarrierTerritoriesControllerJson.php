<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\CarrierTerritories;
use \Neomerx\CoreApi\Converters\CarrierTerritoryConverterGeneric;
use \Neomerx\CoreApi\Server\Http\Controllers\Traits\LanguageFilterTrait;

final class CarrierTerritoriesControllerJson extends BaseControllerJson
{
    use LanguageFilterTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(
            CarrierTerritories::INTERFACE_BIND_NAME,
            App::make(CarrierTerritoryConverterGeneric::BIND_NAME)
        );
    }
}
