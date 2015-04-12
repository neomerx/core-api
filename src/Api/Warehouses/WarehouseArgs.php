<?php namespace Neomerx\CoreApi\Api\Warehouses;

use \Neomerx\Core\Models\Warehouse;
use \Neomerx\CoreApi\Events\EventArgs;

class WarehouseArgs extends EventArgs
{
    /**
     * @var Warehouse
     */
    private $resource;

    /**
     * @param string    $name
     * @param Warehouse $resource
     * @param EventArgs $args
     */
    public function __construct($name, Warehouse $resource, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->resource = $resource;
    }

    /**
     * @return Warehouse
     */
    public function getModel()
    {
        return $this->resource;
    }
}
