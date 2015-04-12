<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\Core\Models\Country;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\Countries;
use \Neomerx\CoreApi\Converters\RegionConverterGeneric;
use \Neomerx\CoreApi\Converters\CountryConverterGeneric;
use \Neomerx\CoreApi\Server\Http\Controllers\Traits\LanguageFilterTrait;

final class CountriesControllerJson extends BaseControllerJson
{
    use LanguageFilterTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Countries::INTERFACE_BIND_NAME, App::make(CountryConverterGeneric::BIND_NAME));
    }

    /**
     * Get all countries in the system.
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
     * Get all regions of the country.
     *
     * @param string $code Country code.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function regions($code)
    {
        return $this->tryAndCatchWrapper('getRegions', [$code]);
    }

    /**
     * @param array  $parameters
     * @param string $languageFilter
     *
     * @return array
     */
    protected function searchImpl(array $parameters, $languageFilter)
    {
        $countries = $this->getApiFacade()->search($parameters);

        /** @var CountryConverterGeneric $converter */
        $converter = $this->getConverter();
        $converter->setLanguageFilter($languageFilter);

        $result = [];
        foreach ($countries as $country) {
            $result[] = $converter->convert($country);
        }

        return [$result, null];
    }

    /**
     * @param string $code
     *
     * @return array
     */
    protected function getRegions($code)
    {
        $regions = $this->getApiFacade()->regions($code);

        $result = [];
        /** @var RegionConverterGeneric $converter */
        /** @noinspection PhpUndefinedMethodInspection */
        $converter = App::make(RegionConverterGeneric::BIND_NAME);
        foreach ($regions as $region) {
            $result[] = $converter->convert($region);
        }

        return [$result, null];
    }
}
