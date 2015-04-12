<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Neomerx\Core\Models\SupplyOrder;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\SupplyOrders;
use \Neomerx\CoreApi\Converters\SupplyOrderConverterGeneric;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class SupplyOrdersControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(SupplyOrders::INTERFACE_BIND_NAME, App::make(SupplyOrderConverterGeneric::BIND_NAME));
    }

    /**
     * Search supply orders.
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
        return [['id' => $order->{SupplyOrder::FIELD_ID}], SymfonyResponse::HTTP_CREATED];
    }

    /**
     * @param array  $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        /** @var SupplyOrderConverterGeneric $converter */
        $converter = $this->getConverter();

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
