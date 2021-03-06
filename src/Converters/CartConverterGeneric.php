<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\Cart\Cart;
use \Neomerx\CoreApi\Api\Cart\CartItem;
use \Neomerx\CoreApi\Api\Cart\CartInterface as Api;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

class CartConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param Cart $cart
     *
     * @return array
     */
    public function convert($cart = null)
    {
        if ($cart === null) {
            return null;
        }

        ($cart instanceof Cart) ?: S\throwEx(new InvalidArgumentException('cart'));

        $result = [];

        $result[Api::PARAM_WEIGHT]        = $cart->getWeight();
        $result[Api::PARAM_QUANTITY]      = $cart->getQuantity();
        $result[Api::PARAM_PRICE_WO_TAX]  = $cart->getPriceWoTax();
        $result[Api::PARAM_MAX_DIMENSION] = $cart->getMaxDimension();

        $cartItems = [];
        /** @var CartItem $cartItem */
        foreach ($cart as $cartItem) {
            $cartItems[] = [
                Api::PARAM_ITEM_SKU          => $cartItem->getVariant()->sku,
                Api::PARAM_ITEM_PRICE_WO_TAX => $cartItem->getVariant()->price_wo_tax,
                Api::PARAM_ITEM_QUANTITY     => $cartItem->getQuantity(),
            ];
        }
        $result[Api::PARAM_ITEMS] = $cartItems;

        return $result;
    }
}
