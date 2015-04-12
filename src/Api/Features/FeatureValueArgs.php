<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\CharacteristicValue;

class FeatureValueArgs extends EventArgs
{
    /**
     * @var CharacteristicValue
     */
    private $value;

    /**
     * @param string              $name
     * @param CharacteristicValue $value
     * @param EventArgs           $args
     */
    public function __construct($name, CharacteristicValue $value, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->value = $value;
    }

    /**
     * @return CharacteristicValue
     */
    public function getModel()
    {
        return $this->value;
    }
}
