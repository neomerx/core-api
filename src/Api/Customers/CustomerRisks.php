<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\CustomerRisk;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Customers\CustomerRiskRepositoryInterface;

class CustomerRisks extends SingleResourceApi implements CustomerRisksInterface
{
    const EVENT_PREFIX = 'Api.CustomerRisk.';

    /**
     * @var CustomerRiskRepositoryInterface
     */
    private $riskRepo;

    /**
     * @param CustomerRiskRepositoryInterface $riskRepo
     */
    public function __construct(CustomerRiskRepositoryInterface $riskRepo)
    {
        $this->riskRepo = $riskRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            CustomerRisk::FIELD_CODE  => SearchGrammar::TYPE_STRING,
            CustomerRisk::FIELD_NAME  => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
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
    protected function getResourceRepository()
    {
        return $this->riskRepo;
    }

    /**
     * @inheritdoc
     * @return CustomerRisk
     */
    protected function getInstance(array $input)
    {
        return $this->riskRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return CustomerRisk
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var CustomerRisk $resource */
        $this->riskRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var CustomerRisk $resource */
        return new CustomerRiskArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }
}
