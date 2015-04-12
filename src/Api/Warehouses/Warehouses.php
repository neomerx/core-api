<?php namespace Neomerx\CoreApi\Api\Warehouses;

use \Neomerx\Core\Models\Store;
use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Warehouse;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Stores\StoreRepositoryInterface;
use \Neomerx\Core\Repositories\Addresses\AddressRepositoryInterface;
use \Neomerx\Core\Repositories\Warehouses\WarehouseRepositoryInterface;

class Warehouses extends SingleResourceApi implements WarehousesInterface
{
    const EVENT_PREFIX = 'Api.Warehouse.';
    const BIND_NAME    = __CLASS__;

    /**
     * @var WarehouseRepositoryInterface
     */
    private $warehouseRepo;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepo;

    /**
     * Constructor.
     *
     * @param WarehouseRepositoryInterface $warehouseRepo
     * @param AddressRepositoryInterface   $addressRepo
     * @param StoreRepositoryInterface     $storeRepo
     */
    public function __construct(
        WarehouseRepositoryInterface $warehouseRepo,
        AddressRepositoryInterface $addressRepo,
        StoreRepositoryInterface $storeRepo
    ) {
        $this->warehouseRepo = $warehouseRepo;
        $this->addressRepo   = $addressRepo;
        $this->storeRepo     = $storeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Warehouse::FIELD_CODE     => SearchGrammar::TYPE_STRING,
            Warehouse::FIELD_NAME     => SearchGrammar::TYPE_STRING,
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
        return $this->warehouseRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Warehouse $resource */
        return new WarehouseArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     * @return Warehouse
     */
    protected function getInstance(array $input)
    {
        /** @var Address $address */
        $address = $this->keyToModel($input, self::PARAM_ID_ADDRESS, $this->addressRepo);

        /** @var Store $store */
        $store = $this->keyToModel($input, self::PARAM_STORE_CODE, $this->storeRepo);

        return $this->warehouseRepo->instance($input, $address, $store);
    }

    /**
     * @inheritdoc
     * @return Warehouse
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Address $address */
        $address = $this->keyToModel($input, self::PARAM_ID_ADDRESS, $this->addressRepo);

        /** @var Store $store */
        $store = $this->keyToModel($input, self::PARAM_ID_ADDRESS, $this->storeRepo);

        /** @var Warehouse $resource */
        $this->warehouseRepo->fill($resource, $input, $address, $store);
        return $resource;
    }

    /**
     * @return Warehouse
     */
    public function getDefault()
    {
        return $this->warehouseRepo->read(Warehouse::DEFAULT_CODE);
    }
}
