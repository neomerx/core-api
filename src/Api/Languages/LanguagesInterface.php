<?php namespace Neomerx\CoreApi\Api\Languages;

use \Neomerx\Core\Models\Language;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

interface LanguagesInterface extends CrudInterface
{
    const PARAM_NAME     = Language::FIELD_NAME;
    const PARAM_ISO_CODE = Language::FIELD_ISO_CODE;

    /**
     * Create language.
     *
     * @param array $input
     *
     * @return Language
     */
    public function create(array $input);

    /**
     * Read language by identifier.
     *
     * @param string $isoCode
     *
     * @return Language
     */
    public function read($isoCode);

    /**
     * Search languages.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
