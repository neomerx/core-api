<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Response;
use \Neomerx\Core\Support as S;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\Inventories;
use \Neomerx\CoreApi\Converters\ConverterInterface;
use \Neomerx\CoreApi\Api\Inventory\InventoriesInterface;
use \Neomerx\Core\Exceptions\InvalidArgumentException;
use \Neomerx\CoreApi\Converters\InventoryConverterGeneric;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class InventoryControllerJson extends BaseController
{
    /**
     * @var InventoriesInterface
     */
    private $apiFacade;

    /**
     * Constructor
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->apiFacade = App::make(Inventories::INTERFACE_BIND_NAME);
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function show($warehouseCode, $sku)
    {
        return $this->tryAndCatchWrapper('showImpl', [$warehouseCode, $sku]);
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function increment($warehouseCode, $sku)
    {
        $quantity  = Input::get('quantity');
        return $this->tryAndCatchWrapper('incrementImpl', [$warehouseCode, $sku, $quantity]);
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function decrement($warehouseCode, $sku)
    {
        $quantity  = Input::get('quantity');
        return $this->tryAndCatchWrapper('decrementImpl', [$warehouseCode, $sku, $quantity]);
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function makeReserve($warehouseCode, $sku)
    {
        $quantity  = Input::get('quantity');
        return $this->tryAndCatchWrapper('makeReserveImpl', [$warehouseCode, $sku, $quantity]);
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function releaseReserve($warehouseCode, $sku)
    {
        $quantity  = Input::get('quantity');
        return $this->tryAndCatchWrapper('releaseReserveImpl', [$warehouseCode, $sku, $quantity]);
    }

    /**
     * @param string|array $data
     * @param int          $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function formatReply($data, $status)
    {
        $response = Response::json($data, $status);
        return $response;
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     *
     * @return array
     */
    protected function showImpl($warehouseCode, $sku)
    {
        $inventory = $this->apiFacade->read($sku, $warehouseCode);
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var ConverterInterface $converter */
        $converter = App::make(InventoryConverterGeneric::class);
        return [$converter->convert($inventory), null];
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     * @param int    $quantity
     *
     * @return array
     */
    protected function incrementImpl($warehouseCode, $sku, $quantity)
    {
        $this->apiFacade->increment($sku, $warehouseCode, $quantity);
        return [null, null];
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     * @param int    $quantity
     *
     * @return array
     */
    protected function decrementImpl($warehouseCode, $sku, $quantity)
    {
        $this->apiFacade->decrement($sku, $warehouseCode, $quantity);
        return [null, null];
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     * @param int    $quantity
     *
     * @return array
     */
    protected function makeReserveImpl($warehouseCode, $sku, $quantity)
    {
        // TODO move input params check to API

        $quantity !== null ?: S\throwEx(new InvalidArgumentException('quantity'));
        (settype($quantity, 'int') and $quantity > 0) ?: S\throwEx(new InvalidArgumentException('quantity'));

        $this->apiFacade->makeReserve($sku, $warehouseCode, $quantity);

        return [null, null];
    }

    /**
     * @param string $warehouseCode
     * @param string $sku
     * @param int    $quantity
     *
     * @return array
     */
    protected function releaseReserveImpl($warehouseCode, $sku, $quantity)
    {
        $quantity !== null ?: S\throwEx(new InvalidArgumentException('quantity'));
        (settype($quantity, 'int') and $quantity > 0) ?: S\throwEx(new InvalidArgumentException('quantity'));

        $this->apiFacade->releaseReserve($sku, $warehouseCode, $quantity);

        return [null, null];
    }
}
