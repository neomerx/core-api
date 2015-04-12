<?php namespace Neomerx\CoreApi\Server\Http\Controllers\Traits;

use \Neomerx\Core\Support as S;

trait LanguageFilterTrait
{
    /**
     * @param array $parameters
     *
     * @return mixed
     */
    private function getLanguageFilter(array $parameters)
    {
        return S\arrayGetValue($parameters, 'language');
    }
}
