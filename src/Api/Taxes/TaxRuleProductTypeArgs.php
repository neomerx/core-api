<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\TaxRuleProductType;

class TaxRuleProductTypeArgs extends EventArgs
{
    /**
     * @var TaxRuleProductType
     */
    private $resource;

    /**
     * @param string             $name
     * @param TaxRuleProductType $resource
     * @param EventArgs          $args
     */
    public function __construct($name, TaxRuleProductType $resource, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->resource = $resource;
    }

    /**
     * @return TaxRuleProductType
     */
    public function getModel()
    {
        return $this->resource;
    }
}
