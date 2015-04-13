<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\TaxRule;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\TaxRuleCustomerType;
use \Neomerx\CoreApi\Api\DependentSingleResourceApi;
use \Neomerx\Core\Repositories\Taxes\TaxRuleRepositoryInterface;
use \Neomerx\Core\Repositories\Customers\CustomerTypeRepositoryInterface;
use \Neomerx\Core\Repositories\Taxes\TaxRuleCustomerTypeRepositoryInterface;

class TaxRuleCustomerTypes extends DependentSingleResourceApi implements TaxRuleCustomerTypesInterface
{
    const EVENT_PREFIX = 'Api.TaxRuleCustomerType.';

    /**
     * @var TaxRuleRepositoryInterface
     */
    private $ruleRepo;

    /**
     * @var TaxRuleCustomerTypeRepositoryInterface
     */
    private $typeRepo;

    /**
     * @var CustomerTypeRepositoryInterface
     */
    private $customerTypeRepo;

    /**
     * @param TaxRuleCustomerTypeRepositoryInterface $typeRepo
     * @param TaxRuleRepositoryInterface             $ruleRepo
     * @param CustomerTypeRepositoryInterface        $customerTypeRepo
     */
    public function __construct(
        TaxRuleCustomerTypeRepositoryInterface $typeRepo,
        TaxRuleRepositoryInterface $ruleRepo,
        CustomerTypeRepositoryInterface $customerTypeRepo
    ) {
        parent::__construct($ruleRepo, self::PARAM_ID_RULE, self::PARAM_ID);

        $this->ruleRepo         = $ruleRepo;
        $this->typeRepo         = $typeRepo;
        $this->customerTypeRepo = $customerTypeRepo;
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
            TaxRuleCustomerType::withType(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            TaxRuleCustomerType::FIELD_ID          => SearchGrammar::TYPE_INT,
            TaxRuleCustomerType::FIELD_ID_TAX_RULE => SearchGrammar::TYPE_INT,
            SearchGrammar::LIMIT_SKIP              => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE              => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var TaxRuleCustomerType $resource */
        return new TaxRuleCustomerTypeArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function instanceWithParent(BaseModel $parentResource, array $input)
    {
        /** @var TaxRule $parentResource */

        /** @var CustomerType $type */
        $type = $this->keyToModel($input, self::PARAM_TYPE_CODE, $this->customerTypeRepo);

        return $this->typeRepo->instance($parentResource, $type);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var TaxRuleCustomerType $resource */

        /** @var TaxRule $rule */
        $rule = $this->keyToModel($input, self::PARAM_ID_RULE, $this->ruleRepo);

        /** @var CustomerType $type */
        $type = $this->keyToModel($input, self::PARAM_TYPE_CODE, $this->customerTypeRepo);

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
