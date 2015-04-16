<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\ObjectType;
use \Neomerx\CoreApi\Events\EventArgs;

/**
 * @package Neomerx\CoreApi
 */
class ObjectTypeArgs extends EventArgs
{
    /**
     * @var ObjectType
     */
    private $resource;

    /**
     * @param string     $name
     * @param ObjectType $objectType
     * @param EventArgs  $args
     */
    public function __construct($name, ObjectType $objectType, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->resource = $objectType;
    }

    /**
     * @return ObjectType
     */
    public function getModel()
    {
        return $this->resource;
    }
}
