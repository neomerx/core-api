<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\Languages;
use \Neomerx\CoreApi\Converters\LanguageConverterGeneric;

final class LanguagesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Languages::INTERFACE_BIND_NAME, App::make(LanguageConverterGeneric::BIND_NAME));
    }

    /**
     * Search languages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->tryAndCatchWrapper('searchImpl', [Input::all()]);
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        $resources = $this->getApiFacade()->search($parameters);

        $result = [];
        foreach ($resources as $resource) {
            $result[] = $this->getConverter()->convert($resource);
        }

        return [$result, null];
    }
}
