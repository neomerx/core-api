<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \App;
use \Response;
use \InvalidArgumentException;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\CoreApi\Support\Config as C;
use \Neomerx\Limoncello\Http\JsonApiTrait;
use \Illuminate\Foundation\Bus\DispatchesJobs;
use \Illuminate\Routing\Controller as BaseController;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Neomerx\JsonApi\Contracts\Document\DocumentInterface;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * @package Neomerx\CoreApi
 */
class BaseJsonApiController extends BaseController
{
    use DispatchesJobs, JsonApiTrait;

    /**
     * @var CrudInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     *
     * @param string $apiName Api bind name.
     */
    public function __construct($apiName)
    {
        $this->apiFacade = App::make($apiName);

        $this->integration = new LaravelIntegration();
        $this->initJsonApiSupport();
    }

    /**
     * Get all resources.
     *
     * @return Response
     */
    public function index()
    {
        $this->checkParametersEmpty();

        $resources = $this->getApiFacade()->search()->all();

        return $this->getContentResponse($resources);
    }

    /**
     * Get resource by code/id.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    public function show($resourceCode)
    {
        $this->checkParametersEmpty();

        $resource = $this->getApiFacade()->read($resourceCode);

        return $this->getContentResponse($resource);
    }

    /**
     * Delete resource.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    public function destroy($resourceCode)
    {
        $this->checkParametersEmpty();

        try {
            $this->getApiFacade()->delete($resourceCode);
        } catch (ModelNotFoundException $exception) {
            // ignore if resource not found
        }

        return $this->getCodeResponse(SymfonyResponse::HTTP_NO_CONTENT);
    }

    /**
     * @return mixed
     */
    protected function getApiFacade()
    {
        return $this->apiFacade;
    }

    /**
     * Get JSON API config.
     *
     * @return array
     */
    protected function getConfig()
    {
        return C::get(C::JSON_API);
    }

    /**
     * Parse input document as a single resource.
     *
     * @param string|null $typeToCheck
     * @param array       $schemaRelationships
     *
     * @return array [$resourceType, $resourceId, $resourceAttributes, $resourceRelShips]
     */
    protected function parseDocumentAsSingleData($typeToCheck = null, array $schemaRelationships = [])
    {
        $data = S\arrayGetValueEx($this->getDocument(), DocumentInterface::KEYWORD_DATA);

        if ($typeToCheck !== null && (isset($data[DocumentInterface::KEYWORD_TYPE]) === false ||
                $data[DocumentInterface::KEYWORD_TYPE] !== $typeToCheck)
        ) {
            $this->getExceptionThrower()->throwConflict();
        }

        if (($resourceType = $typeToCheck) === null) {
            $resourceType = S\arrayGetValue($data, DocumentInterface::KEYWORD_TYPE);
        }

        $idx        = S\arrayGetValue($data, DocumentInterface::KEYWORD_ID);
        $attributes = S\arrayGetValue($data, DocumentInterface::KEYWORD_ATTRIBUTES);
        $relToParse = S\arrayGetValue($data, DocumentInterface::KEYWORD_RELATIONSHIPS);

        $relationships = null;
        if (empty($relToParse) === false && empty($schemaRelationships) === false) {
            foreach ($schemaRelationships as $name => $type) {
                $relationship = S\arrayGetValue($relToParse, $name);
                if (is_array($relationship) === true) {
                    $relData = S\arrayGetValueEx($relationship, DocumentInterface::KEYWORD_DATA);
                    $relationships[$name] = $this->parseResourceObjects($relData, $type, $name);
                }
            }
        }

        return [$resourceType, $idx, $attributes, $relationships];
    }

    /**
     * @param array       $data
     * @param null|string $typeToCheck
     *
     * @return string
     */
    private function parseRelationshipDataId(array $data, $typeToCheck = null)
    {
        if (isset($data[DocumentInterface::KEYWORD_TYPE]) === false ||
            ($typeToCheck !== null && $typeToCheck !== $data[DocumentInterface::KEYWORD_TYPE])
        ) {
            $this->getExceptionThrower()->throwConflict();
        }

        if (isset($data[DocumentInterface::KEYWORD_ID]) === false) {
            $this->getExceptionThrower()->throwConflict();
        }

        return $data[DocumentInterface::KEYWORD_ID];
    }

    /**
     * @param array  $relationshipData
     * @param string $name
     * @param string $type
     *
     * @return mixed
     */
    private function parseResourceObjects($relationshipData, $type, $name)
    {
        // Possible formats for $relData
        // 1) single item    ['type' => '...', 'id' => '...']
        // 2) array of items [['type' => '...', 'id' => '...'], ..., ...]
        // 3) null
        // 4) empty array []

        $resourceRelShips = null;
        if (isset($relationshipData[DocumentInterface::KEYWORD_TYPE]) === true) {
            // if it has 'type' it's a single item
            $resourceRelShips[$name] = $this->parseRelationshipDataId($relationshipData, $type);
        } elseif (is_array($relationshipData) === true) {
            // it's either array of items or empty array
            $ids = [];
            foreach ($relationshipData as $typeAndIdPair) {
                $ids[] = $this->parseRelationshipDataId($typeAndIdPair, $type);
            }
            $resourceRelShips[$name] = $ids;
        } elseif ($relationshipData === null) {
            $resourceRelShips[$name] = null;
        } else {
            // invalid input data
            throw new InvalidArgumentException();
        }

        return $resourceRelShips;
    }
}
