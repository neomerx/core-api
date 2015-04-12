<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Models\TaxRule;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\Core\Models\TaxRuleProductType;
use \Illuminate\Database\Eloquent\Collection;

interface TaxRuleProductTypesInterface extends CrudInterface
{
    const PARAM_ID        = TaxRuleProductType::FIELD_ID;
    const PARAM_ID_RULE   = TaxRuleProductType::FIELD_ID_TAX_RULE;
    const PARAM_TYPE_CODE = ProductTaxType::FIELD_CODE;

    /**
     * Create product tax type rule.
     *
     * @param array $input
     *
     * @return TaxRuleProductType
     */
    public function create(array $input);

    /**
     * Read product tax type rule by identifier.
     *
     * @param string $code
     *
     * @return TaxRuleProductType
     */
    public function read($code);

    /**
     * Search product tax type rules.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);

    /**
     * Create product tax type rule.
     *
     * @param TaxRule $rule
     * @param array   $input
     *
     * @return TaxRuleProductType
     */
    public function createWithRule(TaxRule $rule, array $input);

    /**
     * Read product tax type rule by identifier.
     *
     * @param int $ruleId
     * @param int $resourceId
     *
     * @return TaxRuleProductType
     */
    public function readWithRule($ruleId, $resourceId);

    /**
     * Delete product tax type rule by identifier.
     *
     * @param int $ruleId
     * @param int $resourceId
     *
     * @return void
     */
    public function deleteWithRule($ruleId, $resourceId);
}
