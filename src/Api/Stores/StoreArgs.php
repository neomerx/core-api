<?php namespace Neomerx\CoreApi\Api\Stores;

use \Neomerx\Core\Models\Store;
use \Neomerx\CoreApi\Events\EventArgs;

/**
 * @package Neomerx\CoreApi
 */
class StoreArgs extends EventArgs
{
    /**
     * @var Store
     */
    private $resource;

    /**
     * @param string    $name
     * @param Store     $resource
     * @param EventArgs $args
     */
    public function __construct($name, Store $resource, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->resource = $resource;
    }

    /**
     * @return Store
     */
    public function getModel()
    {
        return $this->resource;
    }
}
