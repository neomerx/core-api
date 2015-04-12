<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Models\Store;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Warehouse;
use \Neomerx\CoreApi\Api\Warehouses\WarehousesInterface as Api;

class WarehouseConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param Warehouse $resource
     *
     * @return array
     */
    public function convert($resource = null)
    {
        if ($resource === null) {
            return null;
        }

        assert('$resource instanceof '.Warehouse::class);

        $result = $resource->attributesToArray();
        $store  = $resource->{Warehouse::FIELD_STORE};
        $result[Api::PARAM_STORE_CODE] = $store === null ? null : $store->{Store::FIELD_CODE};

        return $result;
    }
}
