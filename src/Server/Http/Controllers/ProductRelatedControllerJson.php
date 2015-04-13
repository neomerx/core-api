<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\Related;
use \Neomerx\CoreApi\Converters\ProductRelatedConverterGeneric;

final class ProductRelatedControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Related::INTERFACE_BIND_NAME, App::make(ProductRelatedConverterGeneric::class));
    }
}
