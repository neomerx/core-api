<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Input;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\ImageFormats;
use \Neomerx\CoreApi\Converters\ImageFormatConverterGeneric;

final class ImageFormatsControllerJson extends BaseControllerJson
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(ImageFormats::INTERFACE_BIND_NAME, App::make(ImageFormatConverterGeneric::BIND_NAME));
    }

    /**
     * Get all image formats in the system.
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
        /** @var ImageFormatConverterGeneric $converter */
        $converter = $this->getConverter();

        $result = [];
        foreach ($this->getApiFacade()->search($parameters) as $resource) {
            $result[] = $converter->convert($resource);
        }

        return [$result, null];
    }
}
