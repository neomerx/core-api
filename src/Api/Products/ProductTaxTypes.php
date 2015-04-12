<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Products\ProductTaxTypeRepositoryInterface;

class ProductTaxTypes extends SingleResourceApi implements ProductTaxTypesInterface
{
    const EVENT_PREFIX = 'Api.ProductTaxType.';
    const BIND_NAME    = __CLASS__;

    /**
     * @var ProductTaxTypeRepositoryInterface
     */
    private $typeRepo;

    /**
     * @param ProductTaxTypeRepositoryInterface $typeRepo
     */
    public function __construct(ProductTaxTypeRepositoryInterface $typeRepo)
    {
        $this->typeRepo = $typeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            ProductTaxType::FIELD_CODE => SearchGrammar::TYPE_STRING,
            ProductTaxType::FIELD_NAME => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP  => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE  => SearchGrammar::TYPE_LIMIT,
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
     * @return ProductTaxType
     */
    protected function getInstance(array $input)
    {
        return $this->typeRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return ProductTaxType
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var ProductTaxType $resource */
        $this->typeRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var ProductTaxType $resource */
        return new ProductTaxTypeArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }
}
