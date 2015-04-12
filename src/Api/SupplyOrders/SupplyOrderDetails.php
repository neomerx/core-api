<?php namespace Neomerx\CoreApi\Api\SupplyOrders;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\SupplyOrder;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\DependentSingleResourceApi;
use \Neomerx\Core\Models\SupplyOrderDetails as Model;
use \Neomerx\Core\Repositories\Products\VariantRepositoryInterface;
use \Neomerx\Core\Repositories\Suppliers\SupplyOrderRepositoryInterface;
use \Neomerx\Core\Repositories\Suppliers\SupplyOrderDetailsRepositoryInterface;

class SupplyOrderDetails extends DependentSingleResourceApi implements SupplyOrderDetailsInterface
{
    const EVENT_PREFIX = 'Api.SupplyOrderDetails.';

    /**
     * @var SupplyOrderDetailsRepositoryInterface
     */
    private $detailsRepo;

    /**
     * @var SupplyOrderRepositoryInterface
     */
    private $supplyOrderRepo;

    /**
     * @var VariantRepositoryInterface
     */
    private $variantRepo;

    /**
     * @param SupplyOrderDetailsRepositoryInterface $detailsRepo
     * @param SupplyOrderRepositoryInterface        $orderRepo
     * @param VariantRepositoryInterface            $variantRepo
     */
    public function __construct(
        SupplyOrderDetailsRepositoryInterface $detailsRepo,
        SupplyOrderRepositoryInterface $orderRepo,
        VariantRepositoryInterface $variantRepo
    ) {
        parent::__construct($orderRepo, self::PARAM_ID_SUPPLY_ORDER, self::PARAM_ID);

        $this->detailsRepo     = $detailsRepo;
        $this->supplyOrderRepo = $orderRepo;
        $this->variantRepo     = $variantRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->detailsRepo;
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
            Model::FIELD_ID              => SearchGrammar::TYPE_INT,
            Model::FIELD_ID_SUPPLY_ORDER => SearchGrammar::TYPE_INT,
            Model::FIELD_ID_VARIANT      => SearchGrammar::TYPE_INT,
            SearchGrammar::LIMIT_SKIP    => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE    => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Model $resource */
        return new SupplyOrderDetailsArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function instanceWithParent(BaseModel $parentResource, array $input)
    {
        assert('$parentResource instanceof '.SupplyOrder::class);

        /** @var SupplyOrder $parentResource */

        /** @var Variant $variant */
        $variant = $this->keyToModelEx($input, self::PARAM_SKU, $this->variantRepo);

        $resource = $this->detailsRepo->instance($parentResource, $variant, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Model $resource */

        /** @var SupplyOrder $supplyOrder */
        $supplyOrder = $this->keyToModel($input, self::PARAM_ID_SUPPLY_ORDER, $this->supplyOrderRepo);

        /** @var Variant $variant */
        $variant = $this->keyToModel($input, self::PARAM_SKU, $this->variantRepo);

        $this->detailsRepo->fill($resource, $supplyOrder, $variant, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    public function createWithOrder(SupplyOrder $supplyOrder, array $input)
    {
        return $this->createWith($supplyOrder, $input);
    }

    /**
     * @inheritdoc
     */
    public function readWithOrder($supplyOrderId, $detailsId)
    {
        settype($detailsId, 'int');
        settype($supplyOrderId, 'int');
        return $this->readWith($supplyOrderId, $detailsId);
    }

    /**
     * @inheritdoc
     */
    public function deleteWithOrder($supplyOrderId, $detailsId)
    {
        settype($detailsId, 'int');
        settype($supplyOrderId, 'int');
        $this->deleteWith($supplyOrderId, $detailsId);
    }
}
