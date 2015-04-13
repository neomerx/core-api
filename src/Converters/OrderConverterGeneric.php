<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Models\Store;
use \Neomerx\Core\Models\Order;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Variant;
use \Illuminate\Support\Facades\App;
use \Neomerx\Core\Models\OrderDetails;
use \Neomerx\CoreApi\Api\Orders\OrdersInterface as Api;

class OrderConverterGeneric implements ConverterInterface
{
    /**
     * @var ConverterInterface
     */
    private $addressConverter;

    /**
     * @param ConverterInterface $addressConverter
     */
    public function __construct(ConverterInterface $addressConverter = null)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->addressConverter = $addressConverter ? $addressConverter : App::make(AddressConverterGeneric::class);
    }

    /**
     * Format model to array representation.
     *
     * @param Order $order
     *
     * @return array
     */
    public function convert($order = null)
    {
        if ($order === null) {
            return null;
        }

        assert('$order instanceof '.Order::class);

        //$addressConverter = $this->addressConverter;
        $store            = $order->{Order::FIELD_STORE};

        $result                              = $order->attributesToArray();
        $result[Api::PARAM_STATUS_CODE]      = $order->status->code;
        //$result[Api::PARAM_BILLING_ADDRESS]  = $addressConverter->convert($order->{Order::FIELD_BILLING_ADDRESS});
        //$result[Api::PARAM_SHIPPING_ADDRESS] = $addressConverter->convert($order->{Order::FIELD_SHIPPING_ADDRESS});
        $result[Api::PARAM_STORE_CODE]       = $store !== null ? $store->{Store::FIELD_CODE} : null;

        //unset($addressConverter);
        unset($store);

        $details = [];
        foreach ($order->details as $detailsRow) {
            /** @var OrderDetails $detailsRow */
            $attributes = $detailsRow->attributesToArray();
            $attributes[Variant::FIELD_SKU] = $detailsRow->variant->{Variant::FIELD_SKU};
            $details[] = $attributes;
        }
        $result[Api::PARAM_ORDER_DETAILS] = $details;

        return $result;
    }
}
