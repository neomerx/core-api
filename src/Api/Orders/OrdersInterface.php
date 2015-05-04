<?php namespace Neomerx\CoreApi\Api\Orders;

use \Neomerx\Core\Models\Order;
use \Neomerx\Core\Models\Customer;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\OrderDetails;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface OrdersInterface extends CrudInterface
{
//    const PARAM_ADDRESSES              = 'addresses';
//    const PARAM_ADDRESSES_BILLING      = Order::FIELD_BILLING_ADDRESS;
//    const PARAM_ADDRESSES_SHIPPING     = Order::FIELD_SHIPPING_ADDRESS;
//    const PARAM_ADDRESS_TYPE           = 'address_type';
//    const PARAM_ADDRESS_TYPE_NEW       = 'new';
//    const PARAM_ADDRESS_TYPE_EXISTING  = 'existing';
//    const PARAM_ADDRESS_ID             = 'id';
//
//    const PARAM_CUSTOMER               = Order::FIELD_CUSTOMER;
//    const PARAM_CUSTOMER_TYPE          = 'customer_type';
//    const PARAM_CUSTOMER_TYPE_NEW      = 'new';
//    const PARAM_CUSTOMER_TYPE_EXISTING = 'existing';
//    const PARAM_CUSTOMER_ID            = 'id';
//
//    const PARAM_ORDER_DETAILS          = Order::FIELD_DETAILS;
//    const PARAM_ORDER_DETAILS_SKU      = 'sku';
//    const PARAM_STORE_CODE             = 'store_code';
//    const PARAM_ORDER_DETAILS_QUANTITY = 'quantity';
//
//    const PARAM_ORDER_STATUS_CODE      = 'order_status_code';
//
//    const PARAM_SHIPPING               = 'shipping';
//    const PARAM_SHIPPING_TYPE          = 'type';        // delivery or pickup
//    const PARAM_SHIPPING_TYPE_DELIVERY = 'delivery';
//    const PARAM_SHIPPING_TYPE_PICKUP   = 'pickup';
//    const PARAM_SHIPPING_CARRIER_CODE  = 'carrier_code';// for delivery
//    const PARAM_SHIPPING_PLACE_CODE    = 'place_code';  // for pickup
//
//    const EVENT_PREFIX = 'Api.Order.';

    /** Parameter key */
    const PARAM_ID_CUSTOMER                     = Customer::FIELD_ID;
    /** Parameter key */
    const PARAM_ID_BILLING_ADDRESS              = 'billing_address_id';
    /** Parameter key */
    const PARAM_ID_SHIPPING_ADDRESS             = 'shipping_address_id';
    /** Parameter key */
    const PARAM_STORE_CODE                      = 'store_code';
    /** Parameter key */
    const PARAM_STATUS_CODE                     = 'status_code';
    /** Parameter key */
    const PARAM_CARRIER_CODE                    = 'carrier_code';

    /** Parameter key */
    const PARAM_ORDER_DETAILS                   = Order::FIELD_DETAILS;
    /** Parameter key */
    const PARAM_ORDER_DETAILS_ID                = OrderDetails::FIELD_ID;
    /** Parameter key */
    const PARAM_ORDER_DETAILS_SKU               = 'sku';
    /** Parameter key */
    const PARAM_ORDER_DETAILS_QUANTITY          = OrderDetails::FIELD_QUANTITY;
    /** Parameter key */
    const PARAM_ORDER_DETAILS_PRICE_WO_TAX      = OrderDetails::FIELD_PRICE_WO_TAX;
    /** Parameter key */
    const PARAM_ORDER_DETAILS_ID_SHIPPING_ORDER = OrderDetails::FIELD_ID_SHIPPING_ORDER;

    /** Parameter key */
    const PARAM_BILLING_ADDRESS                 = Order::FIELD_BILLING_ADDRESS;
    /** Parameter key */
    const PARAM_SHIPPING_ADDRESS                = Order::FIELD_SHIPPING_ADDRESS;

    /**
     * Create order.
     *
     * @param array $input
     *
     * @return Order
     */
    public function create(array $input);

    /**
     * Read order by identifier.
     *
     * @param string $code
     *
     * @return Order
     */
    public function read($code);

    /**
     * Search orders.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
