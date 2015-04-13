<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\TaxRule;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\TaxRulePostcode;
use \Neomerx\CoreApi\Api\DependentSingleResourceApi;
use \Neomerx\Core\Repositories\Taxes\TaxRuleRepositoryInterface;
use \Neomerx\Core\Repositories\Taxes\TaxRulePostcodeRepositoryInterface;

class TaxRulePostcodes extends DependentSingleResourceApi implements TaxRulePostcodesInterface
{
    const EVENT_PREFIX = 'Api.TaxRulePostcode.';

    /**
     * @var TaxRuleRepositoryInterface
     */
    private $ruleRepo;

    /**
     * @var TaxRulePostcodeRepositoryInterface
     */
    private $postcodeRepo;

    /**
     * @param TaxRulePostcodeRepositoryInterface $postcodeRepo
     * @param TaxRuleRepositoryInterface         $ruleRepo
     */
    public function __construct(
        TaxRulePostcodeRepositoryInterface $postcodeRepo,
        TaxRuleRepositoryInterface $ruleRepo
    ) {
        parent::__construct($ruleRepo, self::PARAM_ID_RULE, self::PARAM_ID);

        $this->ruleRepo     = $ruleRepo;
        $this->postcodeRepo = $postcodeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->postcodeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            TaxRulePostcode::FIELD_ID            => SearchGrammar::TYPE_INT,
            TaxRulePostcode::FIELD_ID_TAX_RULE   => SearchGrammar::TYPE_INT,
            TaxRulePostcode::FIELD_POSTCODE_TO   => SearchGrammar::TYPE_STRING,
            TaxRulePostcode::FIELD_POSTCODE_FROM => SearchGrammar::TYPE_STRING,
            TaxRulePostcode::FIELD_POSTCODE_MASK => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP            => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE            => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var TaxRulePostcode $resource */
        return new TaxRulePostcodeArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function instanceWithParent(BaseModel $parentResource, array $input)
    {
        /** @var TaxRule $parentResource */

        return $this->postcodeRepo->instance($parentResource, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var TaxRulePostcode $resource */

        /** @var TaxRule $rule */
        $rule = $this->keyToModel($input, self::PARAM_ID_RULE, $this->ruleRepo);

        $this->postcodeRepo->fill($resource, $rule, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    public function createWithRule(TaxRule $rule, array $input)
    {
        return $this->createWith($rule, $input);
    }

    /**
     * @inheritdoc
     */
    public function readWithRule($ruleId, $resourceId)
    {
        settype($ruleId, 'int');
        settype($resourceId, 'int');
        return $this->readWith($ruleId, $resourceId);
    }

    /**
     * @inheritdoc
     */
    public function deleteWithRule($ruleId, $resourceId)
    {
        settype($ruleId, 'int');
        settype($resourceId, 'int');
        $this->deleteWith($ruleId, $resourceId);
    }
}
