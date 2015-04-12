<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\TaxRulePostcode;

class TaxRulePostcodeArgs extends EventArgs
{
    /**
     * @var TaxRulePostcode
     */
    private $rule;

    /**
     * @param string           $name
     * @param TaxRulePostcode $rule
     * @param EventArgs        $args
     */
    public function __construct($name, TaxRulePostcode $rule, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->rule = $rule;
    }

    /**
     * @return TaxRulePostcode
     */
    public function getModel()
    {
        return $this->rule;
    }
}
