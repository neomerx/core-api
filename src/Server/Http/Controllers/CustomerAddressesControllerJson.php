<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Response;
use \Neomerx\Core\Support as S;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\CustomerAddress;
use \Neomerx\Core\Exceptions\InvalidArgumentException;
use \Neomerx\CoreApi\Converters\AddressConverterCustomer;
use \Neomerx\CoreApi\Api\Customers\CustomerAddressesInterface;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class CustomerAddressesControllerJson extends BaseController
{
    /**
     * @var CustomerAddressesInterface
     */
    private $api;

    /**
     * @var AddressConverterCustomer
     */
    private $converter;

    /**
     * Constructor.
     *
     * @param CustomerAddressesInterface $api
     * @param AddressConverterCustomer   $converter
     */
    public function __construct(CustomerAddressesInterface $api, AddressConverterCustomer $converter)
    {
        $this->api       = $api;
        $this->converter = $converter;
    }

    /**
     * Get customer's addresses.
     *
     * @param int $customerId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index($customerId)
    {
        settype($customerId, 'int');
        return $this->tryAndCatchWrapper('indexAddressesImpl', [$customerId]);
    }

    /**
     * @param int $customerId
     * @param int $addressId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function store($customerId, $addressId)
    {
        // TODO convert this whole controller and API to 'dependent' type API

        settype($customerId, 'int');
        settype($addressId, 'int');
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->tryAndCatchWrapper('storeAddressImpl', [$customerId, $addressId, Input::all()]);
    }

    /**
     * Delete customer's address.
     *
     * @param int    $customerId
     * @param int    $addressId
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function destroy($customerId, $addressId, $type)
    {
        settype($customerId, 'int');
        settype($addressId, 'int');
        settype($type, 'string');
        return $this->tryAndCatchWrapper('destroyAddressImpl', [$customerId, $addressId, $type]);
    }

    /**
     * Set customer's default address.
     *
     * @param int    $customerId
     * @param int    $addressId
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function setDefault($customerId, $addressId, $type)
    {
        settype($customerId, 'int');
        settype($addressId, 'int');
        settype($type, 'string');
        return $this->tryAndCatchWrapper('setDefaultAddressImpl', [$customerId, $addressId, $type]);
    }

    /**
     * @param int   $customerId
     *
     * @return array
     */
    protected function indexAddressesImpl($customerId)
    {
        $customerAddresses = $this->api->search([
            CustomerAddressesInterface::PARAM_ID_CUSTOMER => $customerId,
        ]);

        /** @noinspection PhpUndefinedMethodInspection */
        /** @var AddressConverterCustomer $addressConverter */
        $addressConverter = App::make(AddressConverterCustomer::class);

        $result = [];
        foreach ($customerAddresses as $customerAddress) {
            /** @var \Neomerx\Core\Models\CustomerAddress $customerAddress */
            $result[] = $addressConverter->convert($customerAddress);
        }

        return [$result, null];
    }

    /**
     * @param int   $customerId
     * @param int   $addressId
     * @param array $input
     *
     * @return array
     */
    protected function storeAddressImpl($customerId, $addressId, array $input)
    {
        $input = array_merge($input, [
            CustomerAddressesInterface::PARAM_ID_ADDRESS  => $addressId,
            CustomerAddressesInterface::PARAM_ID_CUSTOMER => $customerId,
        ]);

        $this->api->create($input);
        return [null, SymfonyResponse::HTTP_CREATED];
    }

    /**
     * @param int    $customerId
     * @param int    $addressId
     * @param string $type
     *
     * @return array
     */
    protected function destroyAddressImpl($customerId, $addressId, $type)
    {
        $customerAddress = $this->findAddressOrFail($customerId, $addressId, $type);

        $this->api->deleteAddress($customerAddress);

        return [null, null];
    }

    /**
     * @param int    $customerId
     * @param int    $addressId
     * @param string $type
     *
     * @return array
     */
    protected function setDefaultAddressImpl($customerId, $addressId, $type)
    {
        $customerAddress = $this->findAddressOrFail($customerId, $addressId, $type);
        $this->api->setAsDefault($customerAddress);
        return [null, null];
    }

    /**
     * @param string|array $data
     * @param int          $status
     *
     * @return mixed
     */
    protected function formatReply($data, $status)
    {
        $response = Response::json(empty($data) ?  null : $data, $status);
        return $response;
    }

    /**
     * @param int $customerId
     * @param int $addressId
     * @param int $type
     *
     * @return CustomerAddress
     *
     * @throws InvalidArgumentException
     */
    private function findAddressOrFail($customerId, $addressId, $type)
    {
        $addresses = $this->api->search([
            CustomerAddressesInterface::PARAM_ID_ADDRESS   => $addressId,
            CustomerAddressesInterface::PARAM_ID_CUSTOMER  => $customerId,
            CustomerAddressesInterface::PARAM_ADDRESS_TYPE => $type,
            SearchGrammar::LIMIT_TAKE                      => 1
        ]);

        empty($addresses) === false ?: S\throwEx(new InvalidArgumentException('customer address'));

        return $addresses[0];
    }
}
