<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Auth\Permission;
use \Neomerx\Core\Models\Customer;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\CustomerAddress;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Auth\Facades\Permissions;
use \Neomerx\Core\Repositories\Addresses\AddressRepositoryInterface;
use \Neomerx\Core\Repositories\Customers\CustomerRepositoryInterface;
use \Neomerx\Core\Repositories\Customers\CustomerAddressRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerAddresses extends SingleResourceApi implements CustomerAddressesInterface
{
    const EVENT_PREFIX              = 'Api.CustomerAddress.';
    const EVENT_POSTFIX_SET_DEFAULT = 'setDefault';

    /**
     * @var CustomerAddressRepositoryInterface
     */
    private $customerAddressRepo;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @param CustomerAddressRepositoryInterface $customerAddressRepo
     * @param AddressRepositoryInterface         $addressRepo
     * @param CustomerRepositoryInterface        $customerRepo
     */
    public function __construct(
        CustomerAddressRepositoryInterface $customerAddressRepo,
        AddressRepositoryInterface $addressRepo,
        CustomerRepositoryInterface $customerRepo
    ) {
        $this->customerAddressRepo = $customerAddressRepo;
        $this->addressRepo         = $addressRepo;
        $this->customerRepo        = $customerRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->customerAddressRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            CustomerAddress::withAddress(),
            CustomerAddress::withCustomer(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            CustomerAddress::FIELD_ID          => SearchGrammar::TYPE_INT,
            CustomerAddress::FIELD_IS_DEFAULT  => SearchGrammar::TYPE_BOOL,
            CustomerAddress::FIELD_TYPE        => SearchGrammar::TYPE_STRING,
            CustomerAddress::FIELD_ID_ADDRESS  => SearchGrammar::TYPE_INT,
            CustomerAddress::FIELD_ID_CUSTOMER => SearchGrammar::TYPE_INT,
            SearchGrammar::LIMIT_SKIP          => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE          => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var CustomerAddress $resource */
        return new CustomerAddressArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Address $address */
        $address = $this->keyToModelEx($input, self::PARAM_ID_ADDRESS, $this->addressRepo);

        /** @var Customer $customer */
        $customer = $this->keyToModelEx($input, self::PARAM_ID_CUSTOMER, $this->customerRepo);

        return $this->customerAddressRepo->instance($customer, $address, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Address $address */
        $address = $this->keyToModel($input, self::PARAM_ID_ADDRESS, $this->addressRepo);

        /** @var Customer $customer */
        $customer = $this->keyToModel($input, self::PARAM_ID_CUSTOMER, $this->customerRepo);

        /** @var CustomerAddress $resource */
        $this->customerAddressRepo->fill($resource, $customer, $address, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    public function createAddress(Customer $customer, Address $address, array $attributes)
    {
        $customerAddress = $this->customerAddressRepo->instance($customer, $address, $attributes);
        $this->createResource($customerAddress);
        return $customerAddress;
    }

    /**
     * @inheritdoc
     */
    public function searchAddresses(Customer $customer = null, Address $address = null, $type = null, $quantity = null)
    {
        $searchParams = S\arrayFilterNulls([
            CustomerAddress::FIELD_TYPE        => $type,
            CustomerAddress::FIELD_ID_ADDRESS  => $address  === null ? null : $address->{Address::FIELD_ID},
            CustomerAddress::FIELD_ID_CUSTOMER => $customer === null ? null : $customer->{Customer::FIELD_ID},
            SearchGrammar::LIMIT_TAKE          => $quantity,
        ]);

        return $this->customerAddressRepo
            ->search($this->getResourceRelations(), $searchParams, $this->getSearchRules());
    }

    /**
     * @inheritdoc
     */
    public function setAsDefault(CustomerAddress $customerAddress)
    {
        Permissions::check($customerAddress, Permission::edit());
        $this->customerAddressRepo->setAsDefault($customerAddress);

        $this->fireEvent($this->getEvent($customerAddress, self::EVENT_POSTFIX_SET_DEFAULT));
    }

    /**
     * @inheritdoc
     */
    public function deleteAddress(CustomerAddress $customerAddress)
    {
        $this->deleteResource($customerAddress);
    }
}
