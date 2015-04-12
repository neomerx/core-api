<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\CoreApi\Api\Facades\TaxRuleCustomerTypes;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleCustomerTypesInterface;
use \Neomerx\CoreApi\Converters\TaxRuleCustomerTypeConverterGeneric;

final class TaxRuleCustomerTypesControllerJson extends BaseDependentResourceControllerJson
{
    /**
     * @var TaxRuleCustomerTypesInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            app(TaxRuleCustomerTypeConverterGeneric::BIND_NAME),
            TaxRuleCustomerTypesInterface::PARAM_ID_RULE,
            TaxRuleCustomerTypesInterface::PARAM_ID
        );
        $this->apiFacade = app(TaxRuleCustomerTypes::INTERFACE_BIND_NAME);
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
