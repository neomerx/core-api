<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\TaxRuleTerritory;

class TaxRuleTerritoryArgs extends EventArgs
{
    /**
     * @var TaxRuleTerritory
     */
    private $rule;

    /**
     * @param string           $name
     * @param TaxRuleTerritory $rule
     * @param EventArgs        $args
     */
    public function __construct($name, TaxRuleTerritory $rule, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->rule = $rule;
    }

    /**
     * @return TaxRuleTerritory
     */
    public function getModel()
    {
        return $this->rule;
    }
}
