<?php namespace Neomerx\CoreApi\Api\Inventory;

use Neomerx\Core\Repositories\Inventories\InventoryRepositoryInterface;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Events\Event;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\Supplier;
use \Neomerx\Core\Models\Inventory;
use \Neomerx\Core\Models\Warehouse;
use \Illuminate\Support\Facades\DB;
use \Neomerx\Core\Models\SupplyOrder;
use \Neomerx\Core\Exceptions\LogicException;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Inventories implements InventoriesInterface
{
    const EVENT_PREFIX = 'Api.Inventories.';

    /**
     * @var Inventory
     */
    private $inventoryModel;

    /**
     * @var Product
     */
    private $productModel;

    /**
     * @var Variant
     */
    private $variantModel;

    /**
     * @var Supplier
     */
    private $supplierModel;

    /**
     * @var Warehouse
     */
    private $warehouseModel;

    /**
     * @var SupplyOrder
     */
    private $supplyOrderModel;

    /**
     * @var array
     */
    protected static $relations = [
        'warehouse',
    ];

    /**
     * @var InventoryRepositoryInterface
     */
    private $inventoryRepo;

    /**
     * @param Inventory                    $inventory
     * @param Product                      $product
     * @param Variant                      $variant
     * @param Supplier                     $supplier
     * @param Warehouse                    $warehouse
     * @param SupplyOrder                  $supplyOrder
     * @param InventoryRepositoryInterface $inventoryRepo
     */
    public function __construct(
        Inventory $inventory,
        Product $product,
        Variant $variant,
        Supplier $supplier,
        Warehouse $warehouse,
        SupplyOrder $supplyOrder,
        InventoryRepositoryInterface $inventoryRepo
    ) {
        // TODO refactor with repositories instead of direct usage of models

        $this->inventoryModel   = $inventory;
        $this->productModel     = $product;
        $this->variantModel     = $variant;
        $this->supplierModel    = $supplier;
        $this->warehouseModel   = $warehouse;
        $this->supplyOrderModel = $supplyOrder;
        $this->inventoryRepo    = $inventoryRepo;
    }

    /**
     * {@inheritdoc}
     */
    public function read($variantCode, $warehouseCode)
    {
        /** @var Variant $variant */
        $variant = $this->variantModel->selectByCode($variantCode)->firstOrFail();
        /** @var Warehouse $warehouse */
        $warehouse = $this->warehouseModel->selectByCode($warehouseCode)->firstOrFail();

        return $this->readByObjects($variant, $warehouse);
    }

    /**
     * {@inheritdoc}
     */
    public function increment($variantCode, $warehouseCode, $quantity)
    {
        /** @var Variant $variant */
        $variant = $this->variantModel->selectByCode($variantCode)->firstOrFail();
        /** @var Warehouse $warehouse */
        $warehouse = $this->warehouseModel->selectByCode($warehouseCode)->firstOrFail();

        $this->incrementByObjects($variant, $warehouse, $quantity);
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($variantCode, $warehouseCode, $quantity, $includingReserve = false)
    {
        /** @var Variant $variant */
        $variant = $this->variantModel->selectByCode($variantCode)->firstOrFail();
        /** @var Warehouse $warehouse */
        $warehouse = $this->warehouseModel->selectByCode($warehouseCode)->firstOrFail();

        $this->decrementByObjects($variant, $warehouse, $quantity, $includingReserve);
    }

    /**
     * {@inheritdoc}
     */
    public function makeReserve($variantCode, $warehouseCode, $quantity)
    {
        /** @var Variant $variant */
        $variant = $this->variantModel->selectByCode($variantCode)->firstOrFail();
        /** @var Warehouse $warehouse */
        $warehouse = $this->warehouseModel->selectByCode($warehouseCode)->firstOrFail();

        $this->makeReserveByObjects($variant, $warehouse, $quantity);
    }

    /**
     * {@inheritdoc}
     */
    public function releaseReserve($variantCode, $warehouseCode, $quantity)
    {
        /** @var Variant $variant */
        $variant = $this->variantModel->selectByCode($variantCode)->firstOrFail();
        /** @var Warehouse $warehouse */
        $warehouse = $this->warehouseModel->selectByCode($warehouseCode)->firstOrFail();

        $this->releaseReserveByObjects($variant, $warehouse, $quantity);
    }

    /**
     * @param Variant   $item
     * @param Warehouse $warehouse
     * @param int       $quantity
     *
     * @return Inventory
     */
    private function checkInputAndFindInventoryRow(Variant $item, Warehouse $warehouse, $quantity)
    {
        (is_int($quantity) and $quantity > 0) ?: S\throwEx(new InvalidArgumentException('quantity'));

        /** @var \Neomerx\Core\Models\Inventory $inventory */
        $inventory = $this->inventoryModel
            ->selectByVariantAndWarehouse($item->{Variant::FIELD_ID}, $warehouse->{Warehouse::FIELD_ID})
            ->firstOrFail();
        return $inventory;
    }

    /**
     * @param Inventory $inventoryRow
     * @param int       $quantity
     */
    private function incrementReserveInternal(Inventory $inventoryRow, $quantity)
    {
        $availableForReserve = (int)$inventoryRow->in - $inventoryRow->out - $inventoryRow->reserved;
        $availableForReserve >= $quantity ?: S\throwEx(new InvalidArgumentException('quantity'));

        $inventoryRow->incrementReserved($quantity);

        Event::fire(new InventoryArgs(self::EVENT_PREFIX . 'reserveIncreased', $inventoryRow));
    }

    /**
     * @param Inventory $inventoryRow
     * @param int       $quantity
     */
    private function decrementReserveInternal(Inventory $inventoryRow, $quantity)
    {
        $inventoryRow->reserved >= $quantity ?: S\throwEx(new InvalidArgumentException('quantity'));

        $inventoryRow->decrementReserved($quantity);

        Event::fire(new InventoryArgs(self::EVENT_PREFIX . 'reserveDecreased', $inventoryRow));
    }

    /**
     * Set session transaction isolation level as Serializable.
     */
    private function setIsolationSerializable()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $success =  DB::statement(DB::raw('SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE'));
        $success ?: S\throwEx(new LogicException());
    }

    /**
     * @inheritdoc
     */
    public function releaseReserveByObjects(Variant $variant, Warehouse $warehouse, $quantity)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        DB::beginTransaction();
        try {
            $this->setIsolationSerializable();

            $inventoryRow = $this->checkInputAndFindInventoryRow($variant, $warehouse, $quantity);
            $this->decrementReserveInternal($inventoryRow, $quantity);

            $allExecutedOk = true;
        } finally {
            /** @noinspection PhpUndefinedMethodInspection */
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }
    }

    /**
     * @inheritdoc
     */
    public function makeReserveByObjects(Variant $variant, Warehouse $warehouse, $quantity)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        DB::beginTransaction();
        try {
            $this->setIsolationSerializable();

            $inventoryRow = $this->checkInputAndFindInventoryRow($variant, $warehouse, $quantity);
            $this->incrementReserveInternal($inventoryRow, $quantity);

            $allExecutedOk = true;
        } finally {
            /** @noinspection PhpUndefinedMethodInspection */
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function incrementByObjects(Variant $variant, Warehouse $warehouse, $quantity)
    {
        (is_int($quantity) or ctype_digit($quantity)) ?: S\throwEx(new InvalidArgumentException('quantity'));
        $quantity = (int)$quantity;
        $quantity > 0 ?: S\throwEx(new InvalidArgumentException('quantity'));

        $variantId   = $variant->{Variant::FIELD_ID};
        $warehouseId = $warehouse->{Warehouse::FIELD_ID};

        $inventoryRow = null;

        /** @noinspection PhpUndefinedMethodInspection */
        DB::beginTransaction();
        try {
            $this->setIsolationSerializable();

            /** @var \Neomerx\Core\Models\Inventory $inventoryRow */
            $inventoryRow = $this->inventoryModel->selectByVariantAndWarehouse($variantId, $warehouseId)->first();

            if ($inventoryRow !== null) {
                $inventoryRow->incrementIn($quantity);
            } else {
                $inventoryData = [
                    Inventory::FIELD_IN => $quantity,
                ];
                /** @var \Neomerx\Core\Models\Inventory $inventoryRow */
                $inventoryRow = $this->inventoryRepo->instance($variant, $warehouse, $inventoryData);
                $inventoryRow->saveOrFail();
            }

            $allExecutedOk = true;
        } finally {
            /** @noinspection PhpUndefinedMethodInspection */
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        if ($inventoryRow !== null) {
            Event::fire(new InventoryArgs(self::EVENT_PREFIX . 'itemIncreased', $inventoryRow));
        }
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function decrementByObjects(Variant $variant, Warehouse $warehouse, $quantity, $includingReserve = false)
    {
        if (!(is_int($quantity) or ctype_digit($quantity)) or ($quantity = (int)$quantity) <= 0) {
            throw new InvalidArgumentException('quantity');
        }

        $inventoryRow = null;

        /** @noinspection PhpUndefinedMethodInspection */
        DB::beginTransaction();
        try {
            $this->setIsolationSerializable();

            /** @var \Neomerx\Core\Models\Inventory $inventoryRow */
            $inventoryRow = $this->inventoryModel->selectByVariantAndWarehouse(
                $variant->{Variant::FIELD_ID},
                $warehouse->{Warehouse::FIELD_ID}
            )->firstOrFail();

            $outQty      = $inventoryRow->out;
            $reservedQty = $inventoryRow->reserved;
            $itemsLeft   = $inventoryRow->in - $outQty - ($includingReserve ? 0 : $reservedQty) - $quantity;

            $itemsLeft >= 0 ?:  S\throwEx(new InvalidArgumentException('quantity'));

            $inventoryRow->out = $outQty + $quantity;
            $includingReserve ? ($inventoryRow->reserved = $reservedQty - $quantity) : null;

            $inventoryRow->save() ?: S\throwEx(new LogicException());

            $allExecutedOk = true;
        } finally {
            /** @noinspection PhpUndefinedMethodInspection */
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        if ($inventoryRow !== null) {
            Event::fire(new InventoryArgs(self::EVENT_PREFIX . 'itemDecreased', $inventoryRow));
            if ($includingReserve) {
                Event::fire(new InventoryArgs(self::EVENT_PREFIX . 'reserveDecreased', $inventoryRow));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function readByObjects(Variant $variant, Warehouse $warehouse)
    {
        /** @var \Neomerx\Core\Models\Inventory $inventory */
        $inventory = $this->inventoryModel
            ->selectByVariantAndWarehouse($variant->{Variant::FIELD_ID}, $warehouse->{Warehouse::FIELD_ID})
            ->with(static::$relations)
            ->first();
        return $inventory;
    }
}
