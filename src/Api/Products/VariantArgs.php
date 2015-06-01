<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Variant;
use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

/**
 * @package Neomerx\CoreApi
 */
class VariantArgs extends EventArgs
{
    /**
     * @var Variant
     */
    private $model;

    /**
     * @param string    $name
     * @param Variant   $model
     * @param EventArgs $args
     *
     * @throws InvalidArgumentException
     */
    public function __construct($name, Variant $model, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->model = $model;
    }

    /**
     * @return Variant
     */
    public function getModel()
    {
        return $this->model;
    }
}
