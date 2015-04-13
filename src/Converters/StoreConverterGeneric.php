<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Store;

class StoreConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param Store $resource
     *
     * @return array
     */
    public function convert($resource = null)
    {
        if ($resource === null) {
            return null;
        }

        assert('$resource instanceof '.Store::class);

        $result = $resource->attributesToArray();

        return $result;
    }
}
