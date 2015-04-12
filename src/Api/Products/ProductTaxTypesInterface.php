<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ProductTaxType;
use \Illuminate\Database\Eloquent\Collection;

interface ProductTaxTypesInterface extends CrudInterface
{
    const PARAM_CODE = ProductTaxType::FIELD_CODE;
    const PARAM_NAME = ProductTaxType::FIELD_NAME;

    /**
     * Create product tax type.
     *
     * @param array $input
     *
     * @return ProductTaxType
     */
    public function create(array $input);

    /**
     * Read product tax by identifier.
     *
     * @param string $code
     *
     * @return ProductTaxType
     */
    public function read($code);

    /**
     * Search product tax types.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
