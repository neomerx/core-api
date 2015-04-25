<?php namespace Neomerx\CoreApi\Api\Inventory;

use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\Inventory;
use \Neomerx\Core\Models\Warehouse;

/**
 * @package Neomerx\CoreApi
 */
interface InventoriesInterface
{
    const PARAM_SKU            = 'sku';
    const PARAM_WAREHOUSE_CODE = 'warehouse_code';
    const PARAM_IN             = Inventory::FIELD_IN;
    const PARAM_OUT            = Inventory::FIELD_OUT;
    const PARAM_RESERVED       = Inventory::FIELD_RESERVED;
    const PARAM_QUANTITY       = Inventory::FIELD_QUANTITY;

    /**
     * @param Variant   $variant
     * @param Warehouse $warehouse
     * @param int       $quantity
     *
     * @return void
     */
    public function releaseReserveByObjects(Variant $variant, Warehouse $warehouse, $quantity);

    /**
     * @param Variant   $variant
     * @param Warehouse $warehouse
     * @param int       $quantity
     *
     * @return void
     */
    public function makeReserveByObjects(Variant $variant, Warehouse $warehouse, $quantity);

    /**
     * @param Variant   $variant
     * @param Warehouse $warehouse
     * @param int       $quantity
     *
     * @return void
     */
    public function incrementByObjects(Variant $variant, Warehouse $warehouse, $quantity);

    /**
     * @param Variant   $variant
     * @param Warehouse $warehouse
     * @param int       $quantity
     * @param bool      $includingReserve
     *
     * @return void
     */
    public function decrementByObjects(Variant $variant, Warehouse $warehouse, $quantity, $includingReserve = false);

    /**
     * @param Variant   $variant
     * @param Warehouse $warehouse
     *
     * @return Inventory
     */
    public function readByObjects(Variant $variant, Warehouse $warehouse);

    /**
     * @param string $variantCode
     * @param string $warehouseCode
     * @param int    $quantity
     *
     * @return void
     */
    public function releaseReserve($variantCode, $warehouseCode, $quantity);

    /**
     * @param string $variantCode
     * @param string $warehouseCode
     * @param int    $quantity
     *
     * @return void
     */
    public function makeReserve($variantCode, $warehouseCode, $quantity);

    /**
     * @param string $variantCode
     * @param string $warehouseCode
     * @param int    $quantity
     *
     * @return void
     */
    public function increment($variantCode, $warehouseCode, $quantity);

    /**
     * @param string $variantCode
     * @param string $warehouseCode
     * @param int    $quantity
     * @param bool   $includingReserve
     *
     * @return void
     */
    public function decrement($variantCode, $warehouseCode, $quantity, $includingReserve = false);

    /**
     * @param string $variantCode
     * @param string $warehouseCode
     *
     * @return Inventory
     */
    public function read($variantCode, $warehouseCode);
}
