<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\CoreApi\Api\Facades\TaxRuleProductTypes;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleProductTypesInterface;
use \Neomerx\CoreApi\Converters\TaxRuleProductTypeConverterGeneric;

final class TaxRuleProductTypesControllerJson extends BaseDependentResourceControllerJson
{
    /**
     * @var TaxRuleProductTypesInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            app(TaxRuleProductTypeConverterGeneric::class),
            TaxRuleProductTypesInterface::PARAM_ID_RULE,
            TaxRuleProductTypesInterface::PARAM_ID
        );
        $this->apiFacade = app(TaxRuleProductTypes::INTERFACE_BIND_NAME);
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
