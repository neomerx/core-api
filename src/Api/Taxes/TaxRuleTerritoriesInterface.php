<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Models\TaxRule;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\TaxRuleTerritory;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Common\TerritoryParser;

interface TaxRuleTerritoriesInterface extends CrudInterface
{
    const PARAM_ID               = TaxRuleTerritory::FIELD_ID;
    const PARAM_ID_RULE          = TaxRuleTerritory::FIELD_ID_TAX_RULE;
    const PARAM_TERRITORY_CODE   = TerritoryParser::PARAM_CODE;
    const PARAM_TERRITORY_TYPE   = TerritoryParser::PARAM_TYPE;
    const TERRITORY_TYPE_COUNTRY = TerritoryParser::TYPE_COUNTRY;
    const TERRITORY_TYPE_REGION  = TerritoryParser::TYPE_REGION;

    /**
     * Create territory tax rule.
     *
     * @param array $input
     *
     * @return TaxRuleTerritory
     */
    public function create(array $input);

    /**
     * Read territory tax rule by identifier.
     *
     * @param string $code
     *
     * @return TaxRuleTerritory
     */
    public function read($code);

    /**
     * Search territory tax rules.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);

    /**
     * Create territory tax rule.
     *
     * @param TaxRule $rule
     * @param array   $input
     *
     * @return TaxRuleTerritory
     */
    public function createWithRule(TaxRule $rule, array $input);

    /**
     * Read territory tax rule by identifier.
     *
     * @param int $ruleId
     * @param int $resourceId
     *
     * @return TaxRuleTerritory
     */
    public function readWithRule($ruleId, $resourceId);

    /**
     * Delete territory tax rule by identifier.
     *
     * @param int $ruleId
     * @param int $resourceId
     *
     * @return void
     */
    public function deleteWithRule($ruleId, $resourceId);
}
