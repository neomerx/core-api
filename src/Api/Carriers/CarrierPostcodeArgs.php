<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\CarrierPostcode;

class CarrierPostcodeArgs extends EventArgs
{
    /**
     * @var CarrierPostcode
     */
    private $model;

    /**
     * @param string          $name
     * @param CarrierPostcode $model
     * @param EventArgs       $args
     */
    public function __construct($name, CarrierPostcode $model, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->model = $model;
    }

    /**
     * @return CarrierPostcode
     */
    public function getModel()
    {
        return $this->model;
    }
}
