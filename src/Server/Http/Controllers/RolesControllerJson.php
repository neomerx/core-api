<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Neomerx\CoreApi\Api\Facades\Roles;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Converters\RoleConverterGeneric;

final class RolesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Roles::INTERFACE_BIND_NAME, App::make(RoleConverterGeneric::BIND_NAME));
    }

    /**
     * Search roles in the system.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        return $this->tryAndCatchWrapper('searchImpl', [Input::all()]);
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        /** @var RoleConverterGeneric $converter */
        $converter = $this->getConverter();

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
