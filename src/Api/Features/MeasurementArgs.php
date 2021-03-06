<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\Measurement;

/**
 * @package Neomerx\CoreApi
 */
class MeasurementArgs extends EventArgs
{
    /**
     * @var Measurement
     */
    private $measurement;

    /**
     * @param string      $name
     * @param Measurement $measurement
     * @param EventArgs   $args
     */
    public function __construct($name, Measurement $measurement, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->measurement = $measurement;
    }

    /**
     * @return Measurement
     */
    public function getModel()
    {
        return $this->measurement;
    }
}
