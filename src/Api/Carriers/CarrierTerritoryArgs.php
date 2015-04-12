<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\CarrierTerritory;

class CarrierTerritoryArgs extends EventArgs
{
    /**
     * @var CarrierTerritory
     */
    private $model;

    /**
     * @param string           $name
     * @param CarrierTerritory $model
     * @param EventArgs        $args
     */
    public function __construct($name, CarrierTerritory $model, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->model = $model;
    }

    /**
     * @return CarrierTerritory
     */
    public function getModel()
    {
        return $this->model;
    }
}
