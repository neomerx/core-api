<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\Characteristics;
use \Neomerx\CoreApi\Converters\FeatureValueConverterGeneric;
use \Neomerx\CoreApi\Converters\CharacteristicConverterGeneric;
use \Neomerx\CoreApi\Server\Http\Controllers\Traits\LanguageFilterTrait;

final class CharacteristicsControllerJson extends BaseControllerJson
{
    use LanguageFilterTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Characteristics::INTERFACE_BIND_NAME, App::make(CharacteristicConverterGeneric::BIND_NAME));
    }

    /**
     * Search measurements.
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
     * @param $characteristicCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function getValues($characteristicCode)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $input = Input::all();
        return $this->tryAndCatchWrapper('getValuesImpl', [$characteristicCode, $this->getLanguageFilter($input)]);
    }

    /**
     * @param array  $parameters
     * @param string $languageFilter
     *
     * @return array
     */
    protected function searchImpl(array $parameters, $languageFilter)
    {
        /** @var CharacteristicConverterGeneric $converter */
        $converter = $this->getConverter();
        $converter->setLanguageFilter($languageFilter);

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }

    /**
     * @param string $characteristicCode
     * @param string $languageFilter
     *
     * @return array
     */
    protected function getValuesImpl($characteristicCode, $languageFilter)
    {
        /** @var FeatureValueConverterGeneric $converter */
        $converter = app(FeatureValueConverterGeneric::BIND_NAME);
        $converter->setLanguageFilter($languageFilter);

        $result = [];
        foreach ($this->getApiFacade()->getValues($characteristicCode) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
