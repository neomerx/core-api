<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \App;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\CoreApi\Support\Config as C;
use \Neomerx\Limoncello\Http\JsonApiTrait;
use \Illuminate\Foundation\Bus\DispatchesCommands;
use \Illuminate\Routing\Controller as BaseController;
use \Neomerx\JsonApi\Contracts\Document\DocumentInterface;

/**
 * @package Neomerx\CoreApi
 */
class BaseJsonApiController extends BaseController
{
    use DispatchesCommands, JsonApiTrait;
    /**
     * @var CrudInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     *
     * @param string $apiName Api bind name.
     */
    public function __construct($apiName)
    {
        $this->apiFacade = App::make($apiName);

        $this->integration = new LaravelIntegration();
        $this->initJsonApiSupport();
    }

    /**
     * @return mixed
     */
    protected function getApiFacade()
    {
        return $this->apiFacade;
    }

    /**
     * Get JSON API config.
     *
     * @return array
     */
    protected function getConfig()
    {
        return C::get(C::JSON_API);
    }

    /**
     * Get attributes from input 'data' section for one item.
     *
     * @param string|null $checkType
     *
     * @return array|null
     */
    protected function getSingleDataAttributes($checkType = null)
    {
        $document = $this->getDocument();

        if ($checkType !== null && (
                isset($document[DocumentInterface::KEYWORD_DATA][DocumentInterface::KEYWORD_TYPE]) === false ||
                $document[DocumentInterface::KEYWORD_DATA][DocumentInterface::KEYWORD_TYPE] !== $checkType
            )) {
            $this->getExceptionThrower()->throwConflict();
        }

        if (isset($document[DocumentInterface::KEYWORD_DATA][DocumentInterface::KEYWORD_ATTRIBUTES]) === false) {
            return null;
        } else {
            return $document[DocumentInterface::KEYWORD_DATA][DocumentInterface::KEYWORD_ATTRIBUTES];
        }
    }
}
