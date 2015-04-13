<?php namespace Neomerx\CoreApi\Api\ShippingOrders;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\BaseModel;
use \Illuminate\Support\Facades\App;
use \Neomerx\Core\Models\ShippingOrder;
use \Neomerx\CoreApi\Api\Facades\Carriers;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\CoreApi\Api\Carriers\ShippingData;
use \Neomerx\Core\Models\ShippingOrderStatus;
use \Neomerx\CoreApi\Api\Carriers\CarriersInterface;
use \Neomerx\Core\Repositories\Orders\OrderRepositoryInterface;
use \Neomerx\Core\Repositories\Carriers\CarrierRepositoryInterface;
use \Neomerx\Core\Repositories\Orders\ShippingOrderRepositoryInterface;
use \Neomerx\Core\Repositories\Orders\ShippingStatusRepositoryInterface;
use \Neomerx\Core\Repositories\Customers\CustomerTypeRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShippingOrders extends SingleResourceApi implements ShippingOrdersInterface
{
    const EVENT_PREFIX = 'Api.ShippingOrder.';

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    /**
     * @var CarrierRepositoryInterface
     */
    private $carrierRepo;

    /**
     * @var ShippingStatusRepositoryInterface
     */
    private $statusRepo;

    /**
     * @var ShippingOrderRepositoryInterface
     */
    private $shippingOrderRepo;

    /**
     * @param ShippingOrderRepositoryInterface  $shippingOrderRepo
     * @param CustomerTypeRepositoryInterface   $typeRepo
     * @param CarrierRepositoryInterface        $carrierRepo
     * @param ShippingStatusRepositoryInterface $statusRepo
     */
    public function __construct(
        ShippingOrderRepositoryInterface $shippingOrderRepo,
        CustomerTypeRepositoryInterface $typeRepo,
        CarrierRepositoryInterface $carrierRepo,
        ShippingStatusRepositoryInterface $statusRepo
    ) {
        $this->shippingOrderRepo = $shippingOrderRepo;
        $this->orderRepo         = $typeRepo;
        $this->carrierRepo       = $carrierRepo;
        $this->statusRepo        = $statusRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->shippingOrderRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            ShippingOrder::withCarrier(),
            ShippingOrder::withStatus(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            ShippingOrder::FIELD_TRACKING_NUMBER => SearchGrammar::TYPE_STRING,
            'created'                            => [SearchGrammar::TYPE_DATE, ShippingOrder::FIELD_CREATED_AT],
            'updated'                            => [SearchGrammar::TYPE_DATE, ShippingOrder::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP            => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE            => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var ShippingOrder $resource */
        return new ShippingOrderArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Carrier $carrier */
        $carrier = $this->keyToModelEx($input, self::PARAM_CARRIER_CODE, $this->carrierRepo);

        /** @var ShippingOrderStatus $status */
        $status = $this->keyToModelEx($input, self::PARAM_STATUS_CODE, $this->statusRepo);

        return $this->shippingOrderRepo->instance($carrier, $status, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Carrier $carrier */
        $carrier = $this->keyToModel($input, self::PARAM_CARRIER_CODE, $this->carrierRepo);

        /** @var ShippingOrderStatus $status */
        $status = $this->keyToModel($input, self::PARAM_STATUS_CODE, $this->statusRepo);

        /** @var ShippingOrder $resource */
        $this->shippingOrderRepo->fill($resource, $carrier, $status, $input);

        return $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function calculateCosts(ShippingData $shippingData, Carrier $carrier)
    {
        /** @var CarriersInterface $carriers */
        /** @noinspection PhpUndefinedMethodInspection */
        $carriers = App::make(Carriers::INTERFACE_BIND_NAME);
        return $carriers->calculateTariff($shippingData, $carrier);
    }
}
