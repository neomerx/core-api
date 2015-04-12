<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Neomerx\Core\Models\TaxRule;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\TaxRules;
use \Neomerx\CoreApi\Converters\TaxRuleConverterGeneric;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class TaxRulesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(TaxRules::INTERFACE_BIND_NAME, App::make(TaxRuleConverterGeneric::BIND_NAME));
    }

    /**
     * Search tax rules.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        return $this->tryAndCatchWrapper('searchImpl', [Input::all()]);
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        /** @var TaxRuleConverterGeneric $converter */
        $converter = $this->getConverter();

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function createResource(array $input)
    {
        $taxRule = $this->getApiFacade()->create($input);
        return [['id' => $taxRule->{TaxRule::FIELD_ID}], SymfonyResponse::HTTP_CREATED];
    }
}
