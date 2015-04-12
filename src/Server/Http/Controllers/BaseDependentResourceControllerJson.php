<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Response;
use \Illuminate\Http\JsonResponse;
use \Neomerx\Core\Models\BaseModel;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Converters\ConverterInterface;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class BaseDependentResourceControllerJson extends BaseController
{
    /**
     * @param array $parameters
     *
     * @return Collection
     */
    abstract protected function apiSearch(array $parameters);

    /**
     * @param array $input
     *
     * @return BaseModel
     */
    abstract protected function apiCreate(array $input);

    /**
     * @param int $parentId
     * @param int $resourceId
     *
     * @return BaseModel
     */
    abstract protected function apiRead($parentId, $resourceId);

    /**
     * @param int   $resourceId
     * @param array $input
     *
     * @return BaseModel
     */
    abstract protected function apiUpdate($resourceId, array $input);

    /**
     * @param int $parentId
     * @param int $resourceId
     *
     * @return void
     */
    abstract protected function apiDelete($parentId, $resourceId);

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * @var string
     */
    private $parentInputKey;

    /**
     * @var string
     */
    private $resourceInputKey;

    /**
     * Constructor.
     *
     * @param ConverterInterface $converter
     * @param string             $parentInputKey
     * @param string             $resourceInputKey
     */
    public function __construct(ConverterInterface $converter, $parentInputKey, $resourceInputKey)
    {
        $this->converter        = $converter;
        $this->parentInputKey   = $parentInputKey;
        $this->resourceInputKey = $resourceInputKey;
    }

    /**
     * Search resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        $input = Input::all();
        return $this->tryAndCatchWrapper('searchImpl', [$input]);
    }

    /**
     * Create a resource.
     *
     * @param int $parentId
     *
     * @return JsonResponse
     */
    final public function store($parentId)
    {
        return $this->tryAndCatchWrapper('createResource', [$parentId, Input::all()]);
    }

    /**
     * Read the specified resource.
     *
     * @param int $parentId
     * @param int $resourceId
     *
     * @return JsonResponse
     */
    final public function show($parentId, $resourceId)
    {
        return $this->tryAndCatchWrapper('readResource', [$parentId, $resourceId]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $parentId
     * @param int $resourceId
     *
     * @return JsonResponse
     */
    final public function update($parentId, $resourceId)
    {
        return $this->tryAndCatchWrapper('updateResource', [$parentId, $resourceId, Input::all()]);
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param int $parentId
     * @param int $resourceId
     *
     * @return JsonResponse
     */
    final public function destroy($parentId, $resourceId)
    {
        return $this->tryAndCatchWrapper('deleteResource', [$parentId, $resourceId]);
    }

    /**
     * @param array  $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        $result = [];
        foreach ($this->apiSearch($parameters) as $resource) {
            $result[] = $this->converter->convert($resource);
        }

        return [$result, null];
    }

    /**
     * @param int   $parentId
     * @param array $input
     *
     * @return array
     */
    protected function createResource($parentId, array $input)
    {
        $input[$this->parentInputKey] = $parentId;
        $resource = $this->apiCreate($input);
        return [['id' => $resource->{$this->resourceInputKey}], SymfonyResponse::HTTP_CREATED];
    }

    /**
     * @param int $parentId
     * @param int $resourceId
     *
     * @return array
     */
    protected function readResource($parentId, $resourceId)
    {
        $resource = $this->apiRead($parentId, $resourceId);
        return [$this->converter->convert($resource), null];
    }

    /**
     * @param int    $parentId
     * @param string $resourceId
     * @param array  $input
     *
     * @return array
     */
    protected function updateResource($parentId, $resourceId, array $input)
    {
        $input[$this->parentInputKey] = $parentId;
        $this->apiUpdate($resourceId, $input);
        return $this->readResource($parentId, $resourceId);
    }

    /**
     * @param int    $parentId
     * @param string $resourceId
     *
     * @return array
     */
    protected function deleteResource($parentId, $resourceId)
    {
        $this->apiDelete($parentId, $resourceId);
        return [null, null];
    }

    /**
     * @param string|array $data
     * @param int          $status
     *
     * @return JsonResponse
     */
    protected function formatReply($data, $status)
    {
        $response = Response::json(empty($data) ?  null : $data, $status);

        // TODO Make responses comply with http://jsonapi.org/format/
        //$response->headers->set('Content-Type', 'application/vnd.api+json');

        return $response;
    }
}
