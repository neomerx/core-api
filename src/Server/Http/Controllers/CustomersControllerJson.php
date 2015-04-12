<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\Core\Models\Customer;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\Customers;
use \Neomerx\CoreApi\Converters\CustomerConverterWithAddress;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class CustomersControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Customers::INTERFACE_BIND_NAME, App::make(CustomerConverterWithAddress::BIND_NAME));
    }

    /**
     * Search customers.
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
        $customer = $this->getApiFacade()->create($input);
        return [['id' => $customer->{Customer::FIELD_ID}], SymfonyResponse::HTTP_CREATED];
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        $customers = $this->getApiFacade()->search($parameters);

        $result = [];
        foreach ($customers as $customer) {
            /** @var Customer $customer */
            $result[] = $this->getConverter()->convert($customer);
        }

        return [$result, null];
    }
}
