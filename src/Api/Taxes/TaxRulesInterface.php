<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Models\TaxRule;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

interface TaxRulesInterface extends CrudInterface
{
    const PARAM_TAX_CODE = 'tax_code';
    const PARAM_NAME     = TaxRule::FIELD_NAME;
    const PARAM_PRIORITY = TaxRule::FIELD_PRIORITY;

    /**
     * Create tax rule.
     *
     * @param array $input
     *
     * @return TaxRule
     */
    public function create(array $input);

    /**
     * Read tax rule by identifier.
     *
     * @param int $ruleId
     *
     * @return TaxRule
     */
    public function read($ruleId);

    /**
     * Search tax rules.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
