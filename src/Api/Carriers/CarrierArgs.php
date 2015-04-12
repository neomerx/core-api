<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\Core\Models\Carrier;
use \Neomerx\CoreApi\Events\EventArgs;

class CarrierArgs extends EventArgs
{
    /**
     * @var Carrier
     */
    private $model;

    /**
     * @param string    $name
     * @param Carrier   $model
     * @param EventArgs $args
     */
    public function __construct($name, Carrier $model, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->model = $model;
    }

    /**
     * @return Carrier
     */
    public function getModel()
    {
        return $this->model;
    }
}
