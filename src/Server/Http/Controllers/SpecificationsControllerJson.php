<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\Specifications;
use \Neomerx\CoreApi\Converters\SpecificationConverterGeneric;

final class SpecificationsControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Specifications::INTERFACE_BIND_NAME, App::make(SpecificationConverterGeneric::class));
    }
}
