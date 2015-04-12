<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\FeatureValues;
use \Neomerx\CoreApi\Converters\FeatureValueConverterGeneric;
use \Neomerx\CoreApi\Server\Http\Controllers\Traits\LanguageFilterTrait;

final class FeatureValuesControllerJson extends BaseControllerJson
{
    use LanguageFilterTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(FeatureValues::INTERFACE_BIND_NAME, App::make(FeatureValueConverterGeneric::BIND_NAME));
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
     * @param array  $parameters
     * @param string $languageFilter
     *
     * @return array
     */
    protected function searchImpl(array $parameters, $languageFilter)
    {
        /** @var FeatureValueConverterGeneric $converter */
        $converter = $this->getConverter();
        $converter->setLanguageFilter($languageFilter);

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
