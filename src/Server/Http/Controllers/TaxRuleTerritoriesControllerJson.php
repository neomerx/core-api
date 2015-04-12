<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\CoreApi\Api\Facades\TaxRuleTerritories;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleTerritoriesInterface;
use \Neomerx\CoreApi\Converters\TaxRuleTerritoryConverterGeneric;

final class TaxRuleTerritoriesControllerJson extends BaseDependentResourceControllerJson
{
    /**
     * @var TaxRuleTerritoriesInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            app(TaxRuleTerritoryConverterGeneric::BIND_NAME),
            TaxRuleTerritoriesInterface::PARAM_ID_RULE,
            TaxRuleTerritoriesInterface::PARAM_ID
        );
        $this->apiFacade = app(TaxRuleTerritories::INTERFACE_BIND_NAME);
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
        return $this->apiFacade->readWithRule($parentId, $resourceId);
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
        $this->apiFacade->deleteWithRule($parentId, $resourceId);
    }
}
