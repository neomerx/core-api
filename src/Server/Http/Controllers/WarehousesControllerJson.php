<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\Warehouses;
use \Neomerx\CoreApi\Converters\WarehouseConverterGeneric;

final class WarehousesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Warehouses::INTERFACE_BIND_NAME, App::make(WarehouseConverterGeneric::BIND_NAME));
    }

    /**
     * Search warehouses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        return $this->tryAndCatchWrapper('searchImpl', [Input::all()]);
    }

    /**
     * @param array  $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        /** @var WarehouseConverterGeneric $converter */
        $converter = $this->getConverter();

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
