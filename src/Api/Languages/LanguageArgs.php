<?php namespace Neomerx\CoreApi\Api\Languages;

use \Neomerx\Core\Models\Language;
use \Neomerx\CoreApi\Events\EventArgs;

/**
 * @package Neomerx\CoreApi
 */
class LanguageArgs extends EventArgs
{
    /**
     * @var Language
     */
    private $language;

    /**
     * @param string    $name
     * @param Language  $language
     * @param EventArgs $args
     */
    public function __construct($name, Language $language, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->language = $language;
    }

    /**
     * @return Language
     */
    public function getModel()
    {
        return $this->language;
    }
}
