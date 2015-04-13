<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\CarrierCustomerTypes;
use \Neomerx\CoreApi\Converters\CarrierCustomerTypeConverterGeneric;
use \Neomerx\CoreApi\Server\Http\Controllers\Traits\LanguageFilterTrait;

final class CarrierCustomerTypesControllerJson extends BaseControllerJson
{
    use LanguageFilterTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(
            CarrierCustomerTypes::INTERFACE_BIND_NAME,
            App::make(CarrierCustomerTypeConverterGeneric::class)
        );
    }
}
