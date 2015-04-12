<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\Carriers;
use \Neomerx\CoreApi\Api\Carriers\CarriersInterface;
use \Neomerx\CoreApi\Converters\CarrierConverterGeneric;
use \Neomerx\CoreApi\Server\Http\Controllers\Traits\LanguageFilterTrait;

final class CarriersControllerJson extends BaseControllerJson
{
    use LanguageFilterTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Carriers::INTERFACE_BIND_NAME, App::make(CarrierConverterGeneric::BIND_NAME));
    }

    /**
     * Get all carriers in the system.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $input = Input::all();
        return $this->tryAndCatchWrapper('searchImpl', [$input, $this->getLanguageFilter($input)]);
    }

    /**
     * Get all available tariff calculators installed in the system.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function showCalculators()
    {
        return $this->tryAndCatchWrapper('showCalculatorsImpl', []);
    }

    /**
     * @param array  $parameters
     * @param string $languageFilter
     *
     * @return array
     */
    protected function searchImpl(array $parameters, $languageFilter)
    {
        /** @var CarrierConverterGeneric $converter */
        $converter = $this->getConverter();
        $converter->setLanguageFilter($languageFilter);

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }

    /**
     * @return array
     */
    protected function showCalculatorsImpl()
    {
        /** @var CarriersInterface $api */
        $api = $this->getApiFacade();
        return [$api->getAvailableCalculators(), null];
    }
}
