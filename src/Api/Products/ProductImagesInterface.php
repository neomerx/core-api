<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ProductImage;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Images\ImagesInterface;

interface ProductImagesInterface extends CrudInterface
{
    const PARAM_PRODUCT_SKU        = 'product_sku';
    const PARAM_VARIANT_SKU        = 'variant_sku';
    const PARAM_ID_IMAGE           = ProductImage::FIELD_ID_IMAGE;
    const PARAM_ORIGINAL_FILE_DATA = ImagesInterface::PARAM_ORIGINAL_FILE_DATA;
    const PARAM_POSITION           = ProductImage::FIELD_POSITION;
    const PARAM_IS_COVER           = ProductImage::FIELD_IS_COVER;
    const PARAM_PROPERTIES         = ImagesInterface::PARAM_PROPERTIES;
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
