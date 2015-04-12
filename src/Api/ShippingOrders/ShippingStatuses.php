<?php namespace Neomerx\CoreApi\Api\ShippingOrders;

use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Models\ShippingOrderStatus;
use \Neomerx\Core\Repositories\Orders\ShippingStatusRepositoryInterface;

class ShippingStatuses extends SingleResourceApi implements ShippingStatusesInterface
{
    const EVENT_PREFIX = 'Api.ShippingStatus.';
    const BIND_NAME    = __CLASS__;

    /**
     * @var ShippingStatusRepositoryInterface
     */
    private $statusRepo;

    /**
     * @param ShippingStatusRepositoryInterface $statusRepo
     */
    public function __construct(ShippingStatusRepositoryInterface $statusRepo)
    {
        $this->statusRepo = $statusRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            ShippingOrderStatus::FIELD_CODE => SearchGrammar::TYPE_STRING,
            ShippingOrderStatus::FIELD_NAME => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP       => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE       => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->statusRepo;
    }

    /**
     * @inheritdoc
     * @return ShippingOrderStatus
     */
    protected function getInstance(array $input)
    {
        return $this->statusRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return ShippingOrderStatus
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var ShippingOrderStatus $resource */
        $this->statusRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var ShippingOrderStatus $resource */
        return new ShippingStatusArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }
}
