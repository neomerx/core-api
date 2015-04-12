<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Models\Tax;
use \Neomerx\Core\Models\Address;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\CoreApi\Api\Carriers\Tariff;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\CoreApi\Api\Carriers\ShippingData;
use \Illuminate\Database\Eloquent\Collection;

interface TaxesInterface extends CrudInterface
{
    const PARAM_CODE       = Tax::FIELD_CODE;
    const PARAM_EXPRESSION = Tax::FIELD_EXPRESSION;

    /**
     * Create tax.
     *
     * @param array $input
     *
     * @return Tax
     */
    public function create(array $input);

    /**
     * Read tax by identifier.
     *
     * @param string $code
     *
     * @return Tax
     */
    public function read($code);

    /**
     * Search taxes.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);

    /**
     * Get taxes.
     *
     * @param Address        $address
     * @param CustomerType   $customerType
     * @param ProductTaxType $productTaxType
     *
     * @return Collection
     */
    public function getTaxes(Address $address, CustomerType $customerType, ProductTaxType $productTaxType);

    /**
     * Calculate tax rate.
     *
     * @param ShippingData $shippingData
     * @param Tariff       $shipping
     *
     * @return TaxCalculation
     */
    public function calculateTax(ShippingData $shippingData, Tariff $shipping);
}
