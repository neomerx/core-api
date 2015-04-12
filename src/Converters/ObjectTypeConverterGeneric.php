<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\ObjectType;

class ObjectTypeConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param ObjectType $objectType
     *
     * @return array
     */
    public function convert($objectType = null)
    {
        if ($objectType === null) {
            return null;
        }

        assert('$objectType instanceof '.ObjectType::class);

        $result = $objectType->attributesToArray();

        return $result;
    }
}
