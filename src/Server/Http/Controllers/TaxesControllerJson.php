<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\Taxes;
use \Neomerx\CoreApi\Converters\TaxConverterGeneric;

final class TaxesControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Taxes::INTERFACE_BIND_NAME, App::make(TaxConverterGeneric::BIND_NAME));
    }

    /**
     * Search taxes.
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
        /** @var TaxConverterGeneric $converter */
        $converter = $this->getConverter();

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
