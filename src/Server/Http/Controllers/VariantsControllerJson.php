<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\Core\Support as S;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;
use \Neomerx\CoreApi\Api\Facades\Variants;
use \Neomerx\CoreApi\Converters\VariantConverterGeneric;

final class VariantsControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Variants::INTERFACE_BIND_NAME, App::make(VariantConverterGeneric::class));
    }

    /**
     * Search products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final public function index()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $parameters = Input::all();
        return $this->tryAndCatchWrapper('searchImpl', [$parameters]);
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function searchImpl(array $parameters)
    {
        $resources = $this->getApiFacade()->search($parameters);
        return [$resources, null];
    }
}
