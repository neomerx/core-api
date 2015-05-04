<?php namespace Neomerx\CoreApi\Api\Orders;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Store;
use \Neomerx\Core\Models\Order;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\CoreApi\Api\Cart\Cart;
use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\Customer;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Api\Cart\CartItem;
use \Neomerx\Core\Models\OrderStatus;
use \Neomerx\CoreApi\Api\Facades\Taxes;
use \Neomerx\Core\Models\OrderDetails;
use \Neomerx\CoreApi\Api\Facades\Stores;
use \Neomerx\CoreApi\Api\Carriers\Tariff;
use \Neomerx\Core\Models\ShippingOrder;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\CoreApi\Api\Facades\Warehouses;
use \Neomerx\CoreApi\Api\Carriers\ShippingData;
use \Neomerx\CoreApi\Api\Facades\ShippingOrders;
use \Neomerx\CoreApi\Api\Inventory\InventoriesInterface;
use \Neomerx\Core\Repositories\Orders\OrderRepositoryInterface;
use \Neomerx\Core\Repositories\Stores\StoreRepositoryInterface;
use \Neomerx\Core\Repositories\Carriers\CarrierRepositoryInterface;
use \Neomerx\Core\Repositories\Products\VariantRepositoryInterface;
use \Neomerx\Core\Repositories\Addresses\AddressRepositoryInterface;
use \Neomerx\Core\Repositories\Customers\CustomerRepositoryInterface;
use \Neomerx\Core\Repositories\Orders\OrderStatusRepositoryInterface;
use \Neomerx\Core\Repositories\Orders\OrderDetailsRepositoryInterface;
use \Neomerx\Core\Repositories\Orders\ShippingOrderRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Orders extends SingleResourceApi implements OrdersInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.Order.';

    /**
     * @var array
     */
    protected $variantCache;

//    use CreateTrait;
//    use ParseInputTrait;
//
//    /**
//     * @var Order
//     */
//    private $orderModel;
//
//    /**
//     * @var CustomersInterface
//     */
//    private $customersApi;
//
//    /**
//     * @var ShippingOrdersInterface
//     */
//    private $shippingOrdersApi;
//
//    /**
//     * @var InventoriesInterface
//     */
//    private $inventoryApi;
//
//    /**
//     * @var TaxesInterface
//     */
//    private $taxesApi;
//
//    /**
//     * @var OrderStatus
//     */
//    private $orderStatusModel;
//
//    /**
//     * @var Variant
//     */
//    private $variantModel;
//
//    /**
//     * @var Warehouse
//     */
//    private $warehouseModel;
//
//    protected static $orderRelations = [
//        'shippingAddress.region.country',
//        'billingAddress.region.country',
//        'store.address.region.country',
//        Order::FIELD_STATUS,
//        'details.variant',
//        'details',
//        'shippingOrders.status'
//    ];
//
//    /**
//     * Searchable fields of the resource.
//     * Could be used as parameters in search function.
//     *
//     * @var array
//     */
//    protected static $searchRules = [
//        'created'                 => [SearchGrammar::TYPE_DATE, Order::FIELD_CREATED_AT],
//        'updated'                 => [SearchGrammar::TYPE_DATE, Order::FIELD_UPDATED_AT],
//        SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
//        SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
//    ];
//
//    /**
//     * @param CustomersInterface      $customers
//     * @param InventoriesInterface      $inventory
//     * @param Order                   $order
//     * @param OrderStatus             $orderStatus
//     * @param ShippingOrdersInterface $shippingOrders
//     * @param TaxesInterface          $taxes
//     * @param Warehouse               $warehouse
//     * @param Variant                 $variant
//     */
//    public function __construct(
//        CustomersInterface $customers,
//        InventoriesInterface $inventory,
//        Order $order,
//        OrderStatus $orderStatus,
//        ShippingOrdersInterface $shippingOrders,
//        TaxesInterface $taxes,
//        Warehouse $warehouse,
//        Variant $variant
//    ) {
//        $this->orderModel        = $order;
//        $this->orderStatusModel  = $orderStatus;
//        $this->customersApi      = $customers;
//        $this->inventoryApi      = $inventory;
//        $this->shippingOrdersApi = $shippingOrders;
//        $this->taxesApi          = $taxes;
//        $this->warehouseModel    = $warehouse;
//        $this->variantModel      = $variant;
//    }
//
//    /**
//     * @inheritdoc
//     *
//     * @SuppressWarnings(PHPMD.NPathComplexity)
//     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
//     */
//    public function create(array $input)
//    {
//        /** @var \Neomerx\Admin\Api\Cart\Cart $cart */
//        /** @var \Neomerx\Core\Models\Store $store */
//        /** @var \Neomerx\Core\Models\Customer $customer */
//
//        list($carrier, $store,
//            $isNewCustomer, $customerData, $customer,
//            $isNewBilling, $billingAddressData, $billingAddressId,
//            $isNewShipping, $shippingAddressData, $shippingAddressId,
//            $cart) = $this->parseInput($input);
//
//        /** @var \Neomerx\Core\Models\Order $order */
//        $order = null;
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        DB::beginTransaction();
//        try {
//
//            // create customer if required
//            ($isNewCustomer === null or (empty($customerData) and $customer === null)) ?
//                S\throwEx(new InvalidArgumentException(self::PARAM_CUSTOMER)) : null;
//            if ($isNewCustomer) {
//                $customer = $this->customersApi->create($customerData);
//            }
//
//            // create or find addresses
//            list($billingAddress, $shippingAddress) = $this->createOrFindAddresses(
//                $this->customersApi,
//                $customer,
//                $isNewBilling,
//                $billingAddressData,
//                $billingAddressId,
//                $isNewShipping,
//                $shippingAddressData,
//                $shippingAddressId
//            );
//            unset($billingAddressId);
//            unset($shippingAddressId);
//
//            $addressFrom = Stores::getDefault()->address;
//            $shippingData = new ShippingData($customer, $cart, $shippingAddress, $store, $addressFrom);
//
//            // sanity check: shipping address shouldn't be specified with pickup
//            (ShippingData::TYPE_PICKUP === $shippingData->getShippingType() and $shippingAddress !== null) ?
//                S\throwEx(new InvalidArgumentException(self::PARAM_SHIPPING_TYPE_PICKUP)) : null;
//
//            // calculate shipping costs. We assume it costs nothing for pickup. If not change this one -------|
//            $tariff = (ShippingData::TYPE_PICKUP === $shippingData->getShippingType() ? new Tariff(0) : // <--
//                $this->shippingOrdersApi->calculateCosts($shippingData, $carrier));
//
//            // calculate taxes for shipping address which could be store (pickup place) or client's shipping address
//            $taxCalculation = $this->taxesApi->calculateTax($shippingData, $tariff);
//
//            // create an order
//            $order = $this->createNewOrderWithDetails(
//                $shippingData,
//                $billingAddress,
//                $taxCalculation,
//                $tariff,
//                $this->orderStatusModel,
//                $this->inventoryApi
//            );
//
//            $allExecutedOk = true;
//
//        } finally {
//
//            /** @noinspection PhpUndefinedMethodInspection */
//            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
//
//        }
//
//        if ($order !== null) {
//            Event::fire(new OrderArgs(self::EVENT_PREFIX . 'created', $order));
//        }
//
//        return $order;
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function read($orderId)
//    {
//        /** @var \Neomerx\Core\Models\Order $order */
//        $order = $this->orderModel->with(static::$orderRelations)->findOrFail($orderId);
//
//        Permissions::check($order, Permission::view());
//
//        return $order;
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function update($orderId, array $input)
//    {
//        // we do not allow changing order details or shipping address but order status could be changed.
//        $orderStatusCode = S\arrayGetValue($input, self::PARAM_ORDER_STATUS_CODE);
//        !is_null($orderStatusCode) ?: S\throwEx(new InvalidArgumentException(self::PARAM_ORDER_STATUS_CODE));
//
//        /** @var \Neomerx\Core\Models\Order $order */
//        $order = $this->orderModel->findOrFail($orderId);
//        Permissions::check($order, Permission::edit());
//
//        /** @var \Neomerx\Core\Models\OrderStatus $availableStatus */
//        foreach ($order->status->available_statuses as $availableStatus) {
//            if ($availableStatus->code === $orderStatusCode) {
//                $order->{OrderStatus::FIELD_ID} = $availableStatus->{OrderStatus::FIELD_ID};
//                $order->save() ?: S\throwEx(new ValidationException($order->getValidator()));
//                $statusFound = true;
//
//                Event::fire(new OrderArgs(self::EVENT_PREFIX . 'updated', $order));
//
//                break;
//            }
//        }
//
//        isset($statusFound) ?: S\throwEx(new InvalidArgumentException(self::PARAM_ORDER_STATUS_CODE));
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function delete($orderId)
//    {
//        /** @var \Neomerx\Core\Models\Order $order */
//        $order = $this->orderModel->findOrFail($orderId);
//        Permissions::check($order, Permission::delete());
//        $order->deleteOrFail();
//
//        Event::fire(new OrderArgs(self::EVENT_PREFIX . 'deleted', $order));
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function search(array $parameters = [])
//    {
//        /** @noinspection PhpParamsInspection */
//        $builder = $this->orderModel->newQuery()->with(static::$orderRelations);
//
//        // add search parameters if required
//        if (!empty($parameters)) {
//            $parser  = new SearchParser(new SearchGrammar($builder), static::$searchRules);
//            $builder = $parser->buildQuery($parameters);
//        }
//
//        $orders = $builder->get();
//
//        foreach ($orders as $order) {
//            /** @var \Neomerx\Core\Models\Order $order */
//            Permissions::check($order, Permission::view());
//        }
//
//        return $orders;
//    }

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @var OrderStatusRepositoryInterface
     */
    private $statusRepo;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepo;

    /**
     * @var OrderDetailsRepositoryInterface
     */
    private $detailsRepo;

    /**
     * @var VariantRepositoryInterface
     */
    private $variantRepo;

    /**
     * @var ShippingOrderRepositoryInterface
     */
    private $shippingOrderRepo;

    /**
     * @var CarrierRepositoryInterface
     */
    private $carrierRepo;

    /**
     * @var InventoriesInterface
     */
    private $inventoriesApi;

    /**
     * @param OrderRepositoryInterface         $orderRepo
     * @param OrderDetailsRepositoryInterface  $detailsRepo
     * @param OrderStatusRepositoryInterface   $statusRepo
     * @param CustomerRepositoryInterface      $customerRepo
     * @param AddressRepositoryInterface       $addressRepo
     * @param StoreRepositoryInterface         $storeRepo
     * @param VariantRepositoryInterface       $variantRepo
     * @param ShippingOrderRepositoryInterface $shippingOrderRepo
     * @param CarrierRepositoryInterface       $carrierRepo
     * @param InventoriesInterface             $inventories
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        OrderRepositoryInterface $orderRepo,
        OrderDetailsRepositoryInterface $detailsRepo,
        OrderStatusRepositoryInterface $statusRepo,
        CustomerRepositoryInterface $customerRepo,
        AddressRepositoryInterface $addressRepo,
        StoreRepositoryInterface $storeRepo,
        VariantRepositoryInterface $variantRepo,
        ShippingOrderRepositoryInterface $shippingOrderRepo,
        CarrierRepositoryInterface $carrierRepo,
        InventoriesInterface $inventories
    ) {
        $this->orderRepo         = $orderRepo;
        $this->customerRepo      = $customerRepo;
        $this->statusRepo        = $statusRepo;
        $this->addressRepo       = $addressRepo;
        $this->storeRepo         = $storeRepo;
        $this->detailsRepo       = $detailsRepo;
        $this->variantRepo       = $variantRepo;
        $this->shippingOrderRepo = $shippingOrderRepo;
        $this->carrierRepo       = $carrierRepo;
        $this->inventoriesApi    = $inventories;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->orderRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
           Order::withCustomer(),
           Order::withBillingAddress(),
           Order::withShippingAddress(),
           Order::withStore(),
           Order::withStatus(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Order::FIELD_PRODUCTS_TAX  => SearchGrammar::TYPE_FLOAT,
            Order::FIELD_SHIPPING_COST => SearchGrammar::TYPE_FLOAT,
            Order::FIELD_SHIPPING_TAX  => SearchGrammar::TYPE_FLOAT,
            'created'                  => [SearchGrammar::TYPE_DATE, Order::FIELD_CREATED_AT],
            'updated'                  => [SearchGrammar::TYPE_DATE, Order::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP  => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE  => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Order $resource */
        return new OrderArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Cart $cart */
        $cart = $this->readCartFromDetails(S\arrayGetValueEx($input, self::PARAM_ORDER_DETAILS));

        /** @var Customer $customer */
        $customer = $this->keyToModelEx($input, self::PARAM_ID_CUSTOMER, $this->customerRepo);

        /** @var OrderStatus $status */
        $status = $this->readResourceFromRepository(OrderStatus::STATUS_NEW_ORDER, $this->statusRepo);

        /** @var Address $shippingAddress */
        $shippingAddress = $this->keyToModel($input, self::PARAM_ID_SHIPPING_ADDRESS, $this->addressRepo);

        /** @var Address $billingAddress */
        $billingAddress = $this->keyToModel($input, self::PARAM_ID_BILLING_ADDRESS, $this->addressRepo);

        /** @var Store $store */
        $store = $this->keyToModel($input, self::PARAM_STORE_CODE, $this->storeRepo);

        $addressFrom  = Stores::getDefault()->address;
        $shippingData = new ShippingData($customer, $cart, $shippingAddress, $store, $addressFrom);

        // calculate shipping costs
        if (ShippingData::TYPE_PICKUP === $shippingData->getShippingType()) {
            // We assume it costs nothing for pickup. If not change this line
            $tariff = new Tariff(0);
        } else {
            /** @var Carrier $carrier */
            $carrier = $this->keyToModelEx($input, Orders::PARAM_CARRIER_CODE, $this->carrierRepo);
            $tariff  = ShippingOrders::calculateCosts($shippingData, $carrier);
        }

        // calculate taxes for shipping address which could be store (pickup place) or client's shipping address
        $taxCalculation = Taxes::calculateTax($shippingData, $tariff);

        $input[Order::FIELD_SHIPPING_COST] = $tariff->getCost();
        $input[Order::FIELD_SHIPPING_TAX]  = $taxCalculation->getTaxOnShipping();
        $input[Order::FIELD_PRODUCTS_TAX]  = $taxCalculation->getTax();
        $input[Order::FIELD_TAX_DETAILS]   = $this->convertTaxDetailsToString($taxCalculation->getDetails());

        return $this->orderRepo->instance($customer, $status, $input, $shippingAddress, $billingAddress, $store);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Customer $customer */
        $customer = $this->keyToModel($input, self::PARAM_ID_CUSTOMER, $this->customerRepo);

        /** @var OrderStatus $status */
        $status = $this->keyToModel($input, self::PARAM_STATUS_CODE, $this->statusRepo);

        /** @var Address $shippingAddress */
        $shippingAddress = $this->keyToModel($input, self::PARAM_ID_SHIPPING_ADDRESS, $this->addressRepo);

        /** @var Address $billingAddress */
        $billingAddress = $this->keyToModel($input, self::PARAM_ID_BILLING_ADDRESS, $this->addressRepo);

        /** @var Store $store */
        $store = $this->keyToModel($input, self::PARAM_STORE_CODE, $this->storeRepo);

        /** @var Order $resource */
        $this->orderRepo->fill($resource, $customer, $status, $input, $shippingAddress, $billingAddress, $store);

        return $resource;
    }

    /**
     * @param BaseModel $resource
     * @param array     $input
     *
     * @return void
     */
    protected function onCreating(BaseModel $resource, array $input)
    {
        /** @var Order $resource */

        parent::onCreating($resource, $input);

        // order details will be reserved at this warehouse
        $reserveAtWarehouse = Warehouses::getDefault();

        $orderDetailsInput = S\arrayGetValueEx($input, self::PARAM_ORDER_DETAILS);
        foreach ($orderDetailsInput as $detailsInput) {
            $sku      = S\arrayGetValueEx($detailsInput, self::PARAM_ORDER_DETAILS_SKU);
            $variant  = $this->readVariantFromCacheOrRepo($sku);
            $quantity = S\arrayGetValueEx($detailsInput, self::PARAM_ORDER_DETAILS_QUANTITY);

            /** @var ShippingOrder $shippingOrder */
            $shippingOrder = $this->keyToModel(
                $detailsInput,
                self::PARAM_ORDER_DETAILS_ID_SHIPPING_ORDER,
                $this->shippingOrderRepo
            );

            $detailsInput[OrderDetails::FIELD_PRICE_WO_TAX] = $variant->{Variant::FIELD_PRICE_WO_TAX};

            $this->detailsRepo->instance($resource, $variant, $detailsInput, $shippingOrder)->saveOrFail();

            $this->inventoriesApi->makeReserveByObjects($variant, $reserveAtWarehouse, $quantity);
        }
    }

    /**
     * @param BaseModel $resource
     * @param array     $input
     *
     * @return void
     */
    protected function onUpdating(BaseModel $resource, array $input)
    {
        /** @var Order $resource */

        parent::onUpdating($resource, $input);

        $orderDetailsInput = S\arrayGetValue($input, self::PARAM_ORDER_DETAILS);
        if (empty($orderDetailsInput) === true) {
            return;
        }

        $orderDetails = $resource->{Order::FIELD_DETAILS};
        foreach ($orderDetailsInput as $detailsInput) {
            $detailsId = S\arrayGetValueEx($detailsInput, self::PARAM_ORDER_DETAILS_ID);

            /** @var Variant $variant */
            $variant = $this->keyToModel($detailsInput, self::PARAM_ORDER_DETAILS_SKU, $this->variantRepo);
            /** @var ShippingOrder $shippingOrder */
            $shippingOrder = $this->keyToModel(
                $detailsInput,
                self::PARAM_ORDER_DETAILS_ID_SHIPPING_ORDER,
                $this->shippingOrderRepo
            );

            $foundDetails = null;
            foreach ($orderDetails as $details) {
                /** @var OrderDetails $details */
                if ($details->{OrderDetails::FIELD_ID} === $detailsId) {
                    $foundDetails = $details;
                    break;
                }
            }

            if ($foundDetails !== null) {
                $this->detailsRepo->fill($foundDetails, $resource, $variant, $detailsInput, $shippingOrder);
                $foundDetails->saveOrFail();
            }
        }
    }

    /**
     * @param array $orderDetails
     *
     * @return Cart
     */
    protected function readCartFromDetails(array $orderDetails)
    {
        $this->variantCache = [];

        $cart = new Cart();
        foreach ($orderDetails as $detailsInput) {
            $variantSku = S\arrayGetValueEx($detailsInput, self::PARAM_ORDER_DETAILS_SKU);
            $quantity   = S\arrayGetValueEx($detailsInput, self::PARAM_ORDER_DETAILS_QUANTITY);
            /** @var Variant $variant */
            $variant    = $this->readResourceFromRepository($variantSku, $this->variantRepo);
            $cart->push(new CartItem($variant, $quantity));

            $this->variantCache[$variantSku] = $variant;
        }

        return $cart;
    }

    /**
     * @param array $taxDetails
     *
     * @return string
     */
    protected function convertTaxDetailsToString(array $taxDetails)
    {
        return json_encode($taxDetails);
    }

    /**
     * @param string $sku
     *
     * @return Variant
     */
    protected function readVariantFromCacheOrRepo($sku)
    {
        $got = (isset($this->variantCache[$sku]) === true);
        return $got === true ? $this->variantCache[$sku] : $this->readResourceFromRepository($sku, $this->variantRepo);
    }
}
