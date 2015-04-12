<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \App;
use \Response;
use \Neomerx\Core\Support as S;
use \Illuminate\Http\JsonResponse;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\CoreApi\Converters\ConverterInterface;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Implements base class for CRUD operations by JSON protocol.
 * It's intended to be inherited and extended with more methods if needed.
 */
abstract class BaseControllerJson extends BaseController
{
    /**
     * @var CrudInterface
     */
    private $apiFacade;

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * @see CrudInterface
     * @see ConverterInterface
     *
     * @param string             $apiName   Api bind name.
     * @param ConverterInterface $converter
     */
    public function __construct($apiName, ConverterInterface $converter)
    {
        $api = App::make($apiName);
        assert('$api instanceof \\'.CrudInterface::class);
        $this->apiFacade = $api;
        $this->converter = $converter;
    }

    /**
     * @return mixed
     */
    protected function getApiFacade()
    {
        return $this->apiFacade;
    }

    /**
     * @return ConverterInterface
     */
    protected function getConverter()
    {
        return $this->converter;
    }

    /**
     * Create a newly created resource in storage.
     * Default implementation.
     *
     * @return JsonResponse
     */
    final public function store()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $input = Input::all();
        return $this->tryAndCatchWrapper('createResource', [$input]);
    }

    /**
     * Read the specified resource.
     * Default implementation.
     *
     * @param string|int $resourceCode
     *
     * @return JsonResponse
     */
    final public function show($resourceCode)
    {
        return $this->tryAndCatchWrapper('readResource', [$resourceCode]);
    }

    /**
     * Update the specified resource in storage.
     * Default implementation.
     *
     * @param string|int $resourceCode
     *
     * @return JsonResponse
     */
    final public function update($resourceCode)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $input = Input::all();
        return $this->tryAndCatchWrapper('updateResource', [$resourceCode, $input]);
    }

    /**
     * Delete the specified resource from storage.
     * Default implementation.
     *
     * @param string|int $resourceCode
     *
     * @return JsonResponse
     */
    final public function destroy($resourceCode)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $parameters = Input::all();
        return $this->tryAndCatchWrapper('deleteResource', [$resourceCode, $parameters]);
    }

    /**
     * @param array $input
     *
     * @return array<mixed,mixed>
     */
    protected function createResource(array $input)
    {
        $this->apiFacade->create($input);
        return [[], SymfonyResponse::HTTP_CREATED];
    }

    /**
     * @param string $resourceCode
     *
     * @return array
     */
    protected function readResource($resourceCode)
    {
        $resource = $this->apiFacade->read($resourceCode);
        return [$this->converter !== null ? $this->converter->convert($resource) : $resource, null];
    }

    /**
     * @param string $resourceCode
     * @param array  $input
     *
     * @return array
     */
    protected function updateResource($resourceCode, array $input)
    {
        $this->apiFacade->update($resourceCode, $input);
        return $this->readResource($resourceCode);
    }

    /**
     * @param string $resourceCode
     * @param array  $parameters
     *
     * @return array
     */
    protected function deleteResource($resourceCode, array $parameters)
    {
        $this->apiFacade->delete($resourceCode, $parameters);
        return [null, null];
    }

    /**
     * @param string|array $data
     * @param int               $status
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
