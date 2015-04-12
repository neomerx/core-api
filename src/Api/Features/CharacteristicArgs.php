<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\Characteristic;

class CharacteristicArgs extends EventArgs
{
    /**
     * @var Characteristic
     */
    private $characteristic;

    /**
     * @param string         $name
     * @param Characteristic $characteristic
     * @param EventArgs      $args
     */
    public function __construct($name, Characteristic $characteristic, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->characteristic = $characteristic;
    }

    /**
     * @return Characteristic
     */
    public function getModel()
    {
        return $this->characteristic;
    }
}
