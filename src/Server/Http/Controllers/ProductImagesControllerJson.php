<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\ProductImages;
use \Neomerx\CoreApi\Converters\ProductImageConverterGeneric;

final class ProductImagesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(ProductImages::INTERFACE_BIND_NAME, App::make(ProductImageConverterGeneric::BIND_NAME));
    }
}
