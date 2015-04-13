<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Characteristic;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\CharacteristicValue;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Models\CharacteristicValueProperties;
use \Neomerx\Core\Repositories\Features\ValueRepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Features\CharacteristicRepositoryInterface;
use \Neomerx\Core\Repositories\Features\ValuePropertiesRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FeatureValues extends ResourceWithPropertiesApi implements FeatureValuesInterface
{
    const EVENT_PREFIX = 'Api.FeatureValue.';

    /**
     * @var ValueRepositoryInterface
     */
    private $imagesRepo;

    /**
     * @var ValuePropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @var CharacteristicRepositoryInterface
     */
    private $characteristicRepo;

    /**
     * @param ValueRepositoryInterface           $valuesRepo
     * @param ValuePropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface        $languageRepo
     * @param CharacteristicRepositoryInterface  $characteristicRepo
     */
    public function __construct(
        ValueRepositoryInterface $valuesRepo,
        ValuePropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo,
        CharacteristicRepositoryInterface $characteristicRepo
    ) {
        parent::__construct($languageRepo);
        $this->imagesRepo         = $valuesRepo;
        $this->propertiesRepo     = $propertiesRepo;
        $this->characteristicRepo = $characteristicRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->imagesRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [CharacteristicValue::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            CharacteristicValue::FIELD_CODE  => SearchGrammar::TYPE_STRING,
            'created'                 => [SearchGrammar::TYPE_DATE, CharacteristicValue::FIELD_CREATED_AT],
            'updated'                 => [SearchGrammar::TYPE_DATE, CharacteristicValue::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var CharacteristicValue $resource */
        return new FeatureValueArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var CharacteristicValue $resource */
        return $this->propertiesRepo->instance($resource, $language, $attributes);
    }

    /**
     * @inheritdoc
     */
    protected function fillProperty(
        BaseModel $property,
        BaseModel $resource,
        Language $language,
        array $attributes
    ) {
        /** @var CharacteristicValue $resource */
        /** @var CharacteristicValueProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var CharacteristicValue $resource */
        return $resource->{CharacteristicValue::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return CharacteristicValueProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Characteristic $characteristic */
        $characteristic = $this->keyToModelEx($input, self::PARAM_CHARACTERISTIC_CODE, $this->characteristicRepo);
        return $this->imagesRepo->instance($characteristic, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var CharacteristicValue $resource */

        /** @var Characteristic $characteristic */
        $characteristic = $this->keyToModel($input, self::PARAM_CHARACTERISTIC_CODE, $this->characteristicRepo);
        $this->imagesRepo->fill($resource, $characteristic, $input);
        return $resource;
    }
}
