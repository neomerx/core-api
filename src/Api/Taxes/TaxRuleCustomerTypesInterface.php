<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Models\TaxRule;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\Core\Models\TaxRuleCustomerType;
use \Illuminate\Database\Eloquent\Collection;

interface TaxRuleCustomerTypesInterface extends CrudInterface
{
    const PARAM_ID        = TaxRuleCustomerType::FIELD_ID;
    const PARAM_ID_RULE   = TaxRuleCustomerType::FIELD_ID_TAX_RULE;
    const PARAM_TYPE_CODE = CustomerType::FIELD_CODE;

    /**
     * Create customer tax type rule.
     *
     * @param array $input
     *
     * @return TaxRuleCustomerType
     */
    public function create(array $input);

    /**
     * Read customer tax type rule by identifier.
     *
     * @param string $code
     *
     * @return TaxRuleCustomerType
     */
    public function read($code);

    /**
     * Search customer tax type rules.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);

    /**
     * Create customer tax type rule.
     *
     * @param TaxRule $rule
     * @param array   $input
     *
     * @return TaxRuleCustomerType
     */
    public function createWithRule(TaxRule $rule, array $input);

    /**
     * Read customer tax type rule by identifier.
     *
     * @param int $ruleId
     * @param int $resourceId
     *
     * @return TaxRuleCustomerType
     */
    public function readWithRule($ruleId, $resourceId);

    /**
     * Delete customer tax type rule by identifier.
     *
     * @param int $ruleId
     * @param int $resourceId
     *
     * @return void
     */
    public function deleteWithRule($ruleId, $resourceId);
}
