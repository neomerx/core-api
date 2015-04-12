<?php namespace Neomerx\CoreApi\Converters;

interface ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param mixed $object
     *
     * @return null|array<mixed,mixed>
     */
    public function convert($object = null);
}
