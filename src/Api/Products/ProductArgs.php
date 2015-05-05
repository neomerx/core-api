<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

/**
 * @package Neomerx\CoreApi
 */
class ProductArgs extends EventArgs
{
    /**
     * @var BaseModel
     */
    private $model;

    /**
     * @param string    $name
     * @param BaseModel $model
     * @param EventArgs $args
     *
     * @throws InvalidArgumentException
     */
    public function __construct($name, BaseModel $model, EventArgs $args = null)
    {
        assert('($model instanceof '.Product::class.') or ($model instanceof '.Variant::class.')');

        parent::__construct($name, $args);
        $this->model = $model;
    }

    /**
     * @return BaseModel
     */
    public function getModel()
    {
        return $this->model;
    }
}
