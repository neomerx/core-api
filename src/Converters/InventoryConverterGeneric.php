<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\Inventory;
use \Neomerx\Core\Models\Warehouse;
use \Neomerx\CoreApi\Api\Inventory\InventoriesInterface as Api;

class InventoryConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param Inventory $inventory
     *
     * @return array
     */
    public function convert($inventory = null)
    {
        if ($inventory === null) {
            return null;
        }

        assert('$inventory instanceof '.Inventory::class);

        $result = $inventory->attributesToArray();

        $variant   = $inventory->{Inventory::FIELD_VARIANT};
        $warehouse = $inventory->{Inventory::FIELD_WAREHOUSE};

        $result[Api::PARAM_SKU]            = $variant === null   ? null : $variant->{Variant::FIELD_SKU};
        $result[Api::PARAM_WAREHOUSE_CODE] = $warehouse === null ? null : $warehouse->{Warehouse::FIELD_CODE};

        return $result;
    }
}
