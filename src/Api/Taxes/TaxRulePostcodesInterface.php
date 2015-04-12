<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Models\TaxRule;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\TaxRulePostcode;
use \Illuminate\Database\Eloquent\Collection;

interface TaxRulePostcodesInterface extends CrudInterface
{
    const PARAM_ID            = TaxRulePostcode::FIELD_ID;
    const PARAM_ID_RULE       = TaxRulePostcode::FIELD_ID_TAX_RULE;
    const PARAM_POSTCODE_FROM = TaxRulePostcode::FIELD_POSTCODE_FROM;
    const PARAM_POSTCODE_TO   = TaxRulePostcode::FIELD_POSTCODE_TO;
    const PARAM_POSTCODE_MASK = TaxRulePostcode::FIELD_POSTCODE_MASK;

    /**
     * Create postcode tax rule.
     *
     * @param array $input
     *
     * @return TaxRulePostcode
     */
    public function create(array $input);

    /**
     * Read postcode tax rule by identifier.
     *
     * @param string $code
     *
     * @return TaxRulePostcode
     */
    public function read($code);

    /**
     * Search postcode tax rules.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);

    /**
     * Create postcode tax rule.
     *
     * @param TaxRule $rule
     * @param array   $input
     *
     * @return TaxRulePostcode
     */
    public function createWithRule(TaxRule $rule, array $input);

    /**
     * Read postcode tax rule by identifier.
     *
     * @param int $ruleId
     * @param int $resourceId
     *
     * @return TaxRulePostcode
     */
    public function readWithRule($ruleId, $resourceId);

    /**
     * Delete postcode tax rule by identifier.
     *
     * @param int $ruleId
     * @param int $resourceId
     *
     * @return void
     */
    public function deleteWithRule($ruleId, $resourceId);
}
