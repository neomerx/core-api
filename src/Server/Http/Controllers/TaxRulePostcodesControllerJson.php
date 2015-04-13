<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\CoreApi\Api\Facades\TaxRulePostcodes;
use \Neomerx\CoreApi\Api\Taxes\TaxRulePostcodesInterface;
use \Neomerx\CoreApi\Converters\TaxRulePostcodeConverterGeneric;

final class TaxRulePostcodesControllerJson extends BaseDependentResourceControllerJson
{
    /**
     * @var TaxRulePostcodesInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            app(TaxRulePostcodeConverterGeneric::class),
            TaxRulePostcodesInterface::PARAM_ID_RULE,
            TaxRulePostcodesInterface::PARAM_ID
        );
        $this->apiFacade = app(TaxRulePostcodes::INTERFACE_BIND_NAME);
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
