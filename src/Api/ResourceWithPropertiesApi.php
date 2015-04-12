<?php namespace Neomerx\CoreApi\Api;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 */
abstract class ResourceWithPropertiesApi extends SingleResourceApi
{
    /**
     * @var LanguageRepositoryInterface
     */
    private $langRepo;

    /**
     * Get unsaved resource instance.
     *
     * @param BaseModel $resource
     * @param Language  $language
     * @param array     $attributes
     *
     * @return BaseModel
     */
    abstract protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes);

    /**
     * Fill property instance and return it.
     *
     * @param BaseModel $property
     * @param BaseModel $resource
     * @param Language  $language
     * @param array     $attributes
     *
     * @return BaseModel
     */
    abstract protected function fillProperty(
        BaseModel $property,
        BaseModel $resource,
        Language $language,
        array $attributes
    );

    /**
     * Get resource properties.
     *
     * @param BaseModel $resource
     *
     * @return Collection
     */
    abstract protected function getResourceProperties(BaseModel $resource);

    /**
     * Get Language ID field name.
     *
     * @return string
     */
    abstract protected function getPropertyLanguageIdKey();

    /**
     * @param LanguageRepositoryInterface $languageRepository
     */
    public function __construct(LanguageRepositoryInterface $languageRepository)
    {
        $this->langRepo = $languageRepository;
    }

    /**
     * @param BaseModel $resource
     * @param array     $input
     *
     * @return void
     */
    protected function onCreating(BaseModel $resource, array $input)
    {
        parent::onCreating($resource, $input);

        foreach ($this->getPropertiesInput($input) as list($language, $properties)) {
            /** @var Language $language */
            $this->getPropertyInstance($resource, $language, $properties)->saveOrFail();
        }
    }

    /**
     * @param BaseModel $resource
     * @param array     $input
     *
     * @return void
     */
    protected function onUpdating(BaseModel $resource, array $input)
    {
        parent::onUpdating($resource, $input);

        $propertyLangIdKey  = $this->getPropertyLanguageIdKey();
        $resourceProperties = $this->getResourceProperties($resource);

        foreach ($this->getPropertiesInput($input) as list($language, $properties)) {
            /** @var Language $language */
            $languageId = $language->{Language::FIELD_ID};
            $foundProperty = null;
            foreach ($resourceProperties as $property) {
                if ($property->{$propertyLangIdKey} === $languageId) {
                    $foundProperty = $property;
                    break;
                }
            }

            if ($foundProperty === null) {
                // field not found - create new one
                $this->getPropertyInstance($resource, $language, $properties)->saveOrFail();

            } else {
                // field found - update it
                $this->fillProperty($foundProperty, $resource, $language, $properties)->saveOrFail();
            }
        }
    }

    /**
     * @param array $input
     *
     * @return \Generator [Language, array]
     */
    private function getPropertiesInput(array $input)
    {
        foreach (S\arrayGetValue($input, 'properties', []) as $isoCode => $languageSet) {
            yield [$this->langRepo->read($isoCode), $languageSet];
        }
    }
}
