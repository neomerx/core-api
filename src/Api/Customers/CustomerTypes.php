<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Customers\CustomerTypeRepositoryInterface;

class CustomerTypes extends SingleResourceApi implements CustomerTypesInterface
{
    const EVENT_PREFIX = 'Api.CustomerType.';

    /**
     * @var CustomerTypeRepositoryInterface
     */
    private $typeRepo;

    /**
     * @param CustomerTypeRepositoryInterface $typeRepo
     */
    public function __construct(CustomerTypeRepositoryInterface $typeRepo)
    {
        $this->typeRepo = $typeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            CustomerType::FIELD_CODE  => SearchGrammar::TYPE_STRING,
            CustomerType::FIELD_NAME  => SearchGrammar::TYPE_STRING,
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
        return $this->typeRepo;
    }

    /**
     * @inheritdoc
     * @return CustomerType
     */
    protected function getInstance(array $input)
    {
        return $this->typeRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return CustomerType
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var CustomerType $resource */
        $this->typeRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var CustomerType $resource */
        return new CustomerTypeArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }
}
