<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Measurement;
use \Neomerx\Core\Models\Characteristic;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Models\CharacteristicProperties;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Features\MeasurementRepositoryInterface;
use \Neomerx\Core\Repositories\Features\CharacteristicRepositoryInterface;
use \Neomerx\Core\Repositories\Features\CharacteristicPropertiesRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Characteristics extends ResourceWithPropertiesApi implements CharacteristicsInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.Feature.';

    /**
     * @var CharacteristicRepositoryInterface
     */
    private $characteristicRepo;

    /**
     * @var CharacteristicPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @var MeasurementRepositoryInterface
     */
    private $measurementRepo;

    /**
     * @param CharacteristicRepositoryInterface           $characteristicRepo
     * @param CharacteristicPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface                 $languageRepo
     * @param MeasurementRepositoryInterface              $measurementRepo
     */
    public function __construct(
        CharacteristicRepositoryInterface $characteristicRepo,
        CharacteristicPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo,
        MeasurementRepositoryInterface $measurementRepo
    ) {
        parent::__construct($languageRepo);
        $this->characteristicRepo = $characteristicRepo;
        $this->propertiesRepo     = $propertiesRepo;
        $this->measurementRepo    = $measurementRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->characteristicRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Characteristic::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Characteristic::FIELD_CODE => SearchGrammar::TYPE_STRING,
            'created'                  => [SearchGrammar::TYPE_DATE, Characteristic::FIELD_CREATED_AT],
            'updated'                  => [SearchGrammar::TYPE_DATE, Characteristic::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP  => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE  => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Characteristic $resource */
        return new CharacteristicArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Characteristic $resource */
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
        /** @var Characteristic $resource */
        /** @var CharacteristicProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Characteristic $resource */
        return $resource->{Characteristic::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return CharacteristicProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Measurement $measurement */
        $measurement = $this->keyToModel($input, self::PARAM_MEASUREMENT_CODE, $this->measurementRepo);
        return $this->characteristicRepo->instance($input, $measurement);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Characteristic $resource */
        /** @var Measurement $measurement */
        $measurement = $this->keyToModel($input, self::PARAM_MEASUREMENT_CODE, $this->measurementRepo);
        $this->characteristicRepo->fill($resource, $input, $measurement);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    public function getValues($characteristicCode)
    {
        /** @var Characteristic $characteristic */
        $characteristic = $this->read($characteristicCode);
        return $characteristic->values;
    }
}
