<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\ProductRelated;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

class RelatedArgs extends EventArgs
{
    /**
     * @var ProductRelated
     */
    private $model;

    /**
     * @param string         $name
     * @param ProductRelated $model
     * @param EventArgs      $args
     *
     * @throws InvalidArgumentException
     */
    public function __construct($name, ProductRelated $model, EventArgs $args = null)
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
