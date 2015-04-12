<?php namespace Neomerx\CoreApi\Api;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Repositories\RepositoryInterface;

abstract class DependentSingleResourceApi extends SingleResourceApi
{
    /**
     * @var RepositoryInterface
     */
    private $parentRepo;

    /**
     * @var string
     */
    private $parentInputKey;

    /**
     * @var string
     */
    private $resourceInputKey;

    /**
     * @param BaseModel $parentResource
     * @param array     $input
     *
     * @return BaseModel
     */
    abstract protected function instanceWithParent(BaseModel $parentResource, array $input);

    /**
     * @param RepositoryInterface $parentRepo
     * @param string              $parentInputKey
     * @param string              $resourceInputKey
     */
    public function __construct(RepositoryInterface $parentRepo, $parentInputKey, $resourceInputKey)
    {
        $this->parentRepo       = $parentRepo;
        $this->parentInputKey   = $parentInputKey;
        $this->resourceInputKey = $resourceInputKey;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        $parentResource = $this->keyToModelEx($input, $this->parentInputKey, $this->parentRepo);
        return $this->instanceWithParent($parentResource, $input);
    }

    /**
     * Create dependent resource.
     *
     * @param BaseModel $parentResource
     * @param array     $input
     *
     * @return BaseModel
     */
    protected function createWith(BaseModel $parentResource, array $input)
    {
        $resource = $this->instanceWithParent($parentResource, $input);
        $resource->saveOrFail();
        return $resource;
    }

    /**
     * Read resource by identifiers.
     *
     * @param int $parentId
     * @param int $resourceId
     *
     * @return BaseModel
     */
    protected function readWith($parentId, $resourceId)
    {
        $result = $this->search([
            $this->parentInputKey     => $parentId,
            $this->resourceInputKey   => $resourceId,

            SearchGrammar::LIMIT_TAKE => 1,
        ]);

        assert('count($result) <= 1');

        return empty($result) === true ? null : $result[0];
    }

    /**
     * Update resource.
     *
     * @param int   $resourceId
     * @param array $input
     *
     * @return void
     */
    public function update($resourceId, array $input)
    {
        if (empty($input) === false) {
            $parentId = S\arrayGetValueEx($input, $this->parentInputKey);
            $resource = $this->readWith($parentId, $resourceId);
            $this->updateResource($resource, $input);
        }
    }

    /**
     * Delete resource by identifiers.
     *
     * @param int $supplyOrderId
     * @param int $detailsId
     *
     * @return void
     */
    protected function deleteWith($supplyOrderId, $detailsId)
    {
        $resource = $this->readWith($supplyOrderId, $detailsId);
        $this->deleteResource($resource);
    }
}
