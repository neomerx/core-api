<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\ProductCategories;
use \Neomerx\CoreApi\Converters\ProductCategoryConverterGeneric;

final class ProductCategoriesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(
            ProductCategories::INTERFACE_BIND_NAME,
            App::make(ProductCategoryConverterGeneric::BIND_NAME)
        );
    }
}
