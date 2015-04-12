<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Illuminate\Support\Facades\App;
use \Neomerx\Core\Models\ObjectType;
use \Neomerx\CoreApi\Api\Facades\ObjectTypes;
use \Neomerx\CoreApi\Converters\ObjectTypeConverterGeneric;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class ObjectTypesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(ObjectTypes::INTERFACE_BIND_NAME, App::make(ObjectTypeConverterGeneric::BIND_NAME));
    }

    /**
     * Get all object types in the system.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        return $this->tryAndCatchWrapper('searchImpl', [Input::all()]);
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function createResource(array $input)
    {
        $resource = $this->getApiFacade()->create($input);
        return [['id' => $resource->{ObjectType::FIELD_ID}], SymfonyResponse::HTTP_CREATED];
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        /** @var ObjectTypeConverterGeneric $converter */
        $converter = $this->getConverter();

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
