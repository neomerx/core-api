<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\CoreApi\Api\Facades\SupplyOrderDetails;
use \Neomerx\CoreApi\Api\SupplyOrders\SupplyOrderDetailsInterface;
use \Neomerx\CoreApi\Converters\SupplyOrderDetailsConverterGeneric;

final class SupplyOrderDetailsControllerJson extends BaseDependentResourceControllerJson
{
    /**
     * @var SupplyOrderDetailsInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            app(SupplyOrderDetailsConverterGeneric::BIND_NAME),
            SupplyOrderDetailsInterface::PARAM_ID_SUPPLY_ORDER,
            SupplyOrderDetailsInterface::PARAM_ID
        );
        $this->apiFacade = app(SupplyOrderDetails::INTERFACE_BIND_NAME);
    }

    /**
     * @inheritdoc
     */
    protected function apiSearch(array $parameters)
    {
        return $this->apiFacade->search($parameters);
    }

    /**
     * @inheritdoc
     */
    protected function apiCreate(array $input)
    {
        return $this->apiFacade->create($input);
    }

    /**
     * @inheritdoc
     */
    protected function apiRead($parentId, $resourceId)
    {
        return $this->apiFacade->readWithOrder($parentId, $resourceId);
    }

    /**
     * @inheritdoc
     */
    protected function apiUpdate($resourceId, array $input)
    {
        $this->apiFacade->update($resourceId, $input);
    }

    /**
     * @inheritdoc
     */
    protected function apiDelete($parentId, $resourceId)
    {
        $this->apiFacade->deleteWithOrder($parentId, $resourceId);
    }
}
