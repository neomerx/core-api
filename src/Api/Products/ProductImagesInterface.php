<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ProductImage;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Images\ImagesInterface;

/**
 * @package Neomerx\CoreApi
 */
interface ProductImagesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_PRODUCT_SKU        = 'product_sku';
    /** Parameter key */
    const PARAM_VARIANT_SKU        = 'variant_sku';
    /** Parameter key */
    const PARAM_ID_IMAGE           = ProductImage::FIELD_ID_IMAGE;
    /** Parameter key */
    const PARAM_ORIGINAL_FILE_DATA = ImagesInterface::PARAM_ORIGINAL_FILE_DATA;
    /** Parameter key */
    const PARAM_POSITION           = ProductImage::FIELD_POSITION;
    /** Parameter key */
    const PARAM_IS_COVER           = ProductImage::FIELD_IS_COVER;
    /** Parameter key */
    const PARAM_PROPERTIES         = ImagesInterface::PARAM_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_ALT     = ImagesInterface::PARAM_PROPERTIES_ALT;

    /**
     * Create image format.
     *
     * @param array $input
     *
     * @return ProductImage
     */
    public function create(array $input);

    /**
     * Read image by identifier.
     *
     * @param int $idx
     *
     * @return ProductImage
     */
    public function read($idx);

    /**
     * Search product images.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
