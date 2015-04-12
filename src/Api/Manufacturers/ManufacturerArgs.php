<?php namespace Neomerx\CoreApi\Api\Manufacturers;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\Manufacturer;

class ManufacturerArgs extends EventArgs
{
    /**
     * @var Manufacturer
     */
    private $manufacturer;

    /**
     * @param string       $name
     * @param Manufacturer $manufacturer
     * @param EventArgs    $args
     */
    public function __construct($name, Manufacturer $manufacturer, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->manufacturer = $manufacturer;
    }

    /**
     * @return Manufacturer
     */
    public function getModel()
    {
        return $this->manufacturer;
    }
}
