<?php namespace Neomerx\CoreApi\Api\Inventory;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\Inventory;

/**
 * @package Neomerx\CoreApi
 */
class InventoryArgs extends EventArgs
{
    /**
     * @var Inventory
     */
    private $inventory;

    /**
     * @param string    $name
     * @param Inventory $inventory
     * @param EventArgs $args
     */
    public function __construct($name, Inventory $inventory, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->inventory = $inventory;
    }

    /**
     * @return Inventory
     */
    public function getModel()
    {
        return $this->inventory;
    }
}
