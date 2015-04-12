<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\ProductImage;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

class ProductImageArgs extends EventArgs
{
    /**
     * @var ProductImage
     */
    private $model;

    /**
     * @param string         $name
     * @param ProductImage $model
     * @param EventArgs      $args
     *
     * @throws InvalidArgumentException
     */
    public function __construct($name, ProductImage $model, EventArgs $args = null)
    {
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
