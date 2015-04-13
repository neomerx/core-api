<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\OrderStatuses;
use \Neomerx\CoreApi\Converters\OrderStatusConverterGeneric;

final class OrderStatusesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(OrderStatuses::INTERFACE_BIND_NAME, App::make(OrderStatusConverterGeneric::class));
    }


    /**
     * Search customer types.
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
        $resources = $this->getApiFacade()->search($parameters);

        $result = [];
        foreach ($resources as $resource) {
            $result[] = $this->getConverter()->convert($resource);
        }

        return [$result, null];
    }
}
