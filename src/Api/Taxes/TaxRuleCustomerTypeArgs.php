<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\TaxRuleCustomerType;

class TaxRuleCustomerTypeArgs extends EventArgs
{
    /**
     * @var TaxRuleCustomerType
     */
    private $resource;

    /**
     * @param string              $name
     * @param TaxRuleCustomerType $resource
     * @param EventArgs           $args
     */
    public function __construct($name, TaxRuleCustomerType $resource, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->resource = $resource;
    }

    /**
     * @return TaxRuleCustomerType
     */
    public function getModel()
    {
        return $this->resource;
    }
}
