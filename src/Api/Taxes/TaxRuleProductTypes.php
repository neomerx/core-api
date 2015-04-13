<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\TaxRule;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\TaxRuleProductType;
use \Neomerx\CoreApi\Api\DependentSingleResourceApi;
use \Neomerx\Core\Repositories\Taxes\TaxRuleRepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductTaxTypeRepositoryInterface;
use \Neomerx\Core\Repositories\Taxes\TaxRuleProductTypeRepositoryInterface;

class TaxRuleProductTypes extends DependentSingleResourceApi implements TaxRuleProductTypesInterface
{
    const EVENT_PREFIX = 'Api.TaxRuleProductType.';

    /**
     * @var TaxRuleRepositoryInterface
     */
    private $ruleRepo;

    /**
     * @var TaxRuleProductTypeRepositoryInterface
     */
    private $typeRepo;

    /**
     * @var ProductTaxTypeRepositoryInterface
     */
    private $productTypeRepo;

    /**
     * @param TaxRuleProductTypeRepositoryInterface $typeRepo
     * @param TaxRuleRepositoryInterface            $ruleRepo
     * @param ProductTaxTypeRepositoryInterface     $productTypeRepo
     */
    public function __construct(
        TaxRuleProductTypeRepositoryInterface $typeRepo,
        TaxRuleRepositoryInterface $ruleRepo,
        ProductTaxTypeRepositoryInterface $productTypeRepo
    ) {
        parent::__construct($ruleRepo, self::PARAM_ID_RULE, self::PARAM_ID);

        $this->ruleRepo        = $ruleRepo;
        $this->typeRepo        = $typeRepo;
        $this->productTypeRepo = $productTypeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->typeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            TaxRuleProductType::withType(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            TaxRuleProductType::FIELD_ID          => SearchGrammar::TYPE_INT,
            TaxRuleProductType::FIELD_ID_TAX_RULE => SearchGrammar::TYPE_INT,
            SearchGrammar::LIMIT_SKIP             => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE             => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var TaxRuleProductType $resource */
        return new TaxRuleProductTypeArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function instanceWithParent(BaseModel $parentResource, array $input)
    {
        /** @var TaxRule $parentResource */

        /** @var ProductTaxType $type */
        $type = $this->keyToModel($input, self::PARAM_TYPE_CODE, $this->productTypeRepo);

        return $this->typeRepo->instance($parentResource, $type);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var TaxRuleProductType $resource */

        /** @var TaxRule $rule */
        $rule = $this->keyToModel($input, self::PARAM_ID_RULE, $this->ruleRepo);

        /** @var ProductTaxType $type */
        $type = $this->keyToModel($input, self::PARAM_TYPE_CODE, $this->productTypeRepo);

        $this->typeRepo->fill($resource, $rule, $type);

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
