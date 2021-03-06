<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\Core\Models\Order;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\Orders;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Converters\OrderConverterGeneric;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class OrdersControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Orders::INTERFACE_BIND_NAME, App::make(OrderConverterGeneric::class));
    }

    /**
     * Search orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $input = Input::all();
        return $this->tryAndCatchWrapper('searchImpl', [$input]);
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function createResource(array $input)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $order = $this->getApiFacade()->create($input);
        return [['id' => $order->{Order::FIELD_ID}], SymfonyResponse::HTTP_CREATED];
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $this->getConverter()->convert($resource);
        }

        return [$result, null];
    }
}
