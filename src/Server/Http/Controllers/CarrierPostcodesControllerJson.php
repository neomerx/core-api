<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\CarrierPostcodes;
use \Neomerx\CoreApi\Converters\CarrierPostcodeConverterGeneric;
use \Neomerx\CoreApi\Server\Http\Controllers\Traits\LanguageFilterTrait;

final class CarrierPostcodesControllerJson extends BaseControllerJson
{
    use LanguageFilterTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(
            CarrierPostcodes::INTERFACE_BIND_NAME,
            App::make(CarrierPostcodeConverterGeneric::BIND_NAME)
        );
    }
}
