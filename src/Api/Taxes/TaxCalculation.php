<?php namespace Neomerx\CoreApi\Api\Taxes;

class TaxCalculation
{
    /**
     * @var float
     */
    private $tax;

    /**
     * @var array
     */
    private $details;

    /**
     * @var float
     */
    private $taxOnShipping;

    /**
     * @param float $tax
     * @param array $details
     * @param float $taxOnShipping
     */
    public function __construct($tax, array $details, $taxOnShipping)
    {
        $this->tax           = $tax;
        $this->details       = $details;
        $this->taxOnShipping = $taxOnShipping;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return float
     */
    public function getTaxOnShipping()
    {
        return $this->taxOnShipping;
    }

    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }
}
