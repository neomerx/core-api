<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Measurement;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\MeasurementProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Features\MeasurementRepositoryInterface;
use \Neomerx\Core\Repositories\Features\MeasurementPropertiesRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 */
class Measurements extends ResourceWithPropertiesApi implements MeasurementsInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.Measurement.';

    /**
     * @var MeasurementRepositoryInterface
     */
    private $measurementRepo;

    /**
     * @var MeasurementPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @param MeasurementRepositoryInterface           $measurementRepo
     * @param MeasurementPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface              $languageRepo
     */
    public function __construct(
        MeasurementRepositoryInterface $measurementRepo,
        MeasurementPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo
    ) {
        parent::__construct($languageRepo);
        $this->measurementRepo = $measurementRepo;
        $this->propertiesRepo  = $propertiesRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->measurementRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Measurement::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Measurement::FIELD_CODE   => SearchGrammar::TYPE_STRING,
            'created'                 => [SearchGrammar::TYPE_DATE, Measurement::FIELD_CREATED_AT],
            'updated'                 => [SearchGrammar::TYPE_DATE, Measurement::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Measurement $resource */
        return new MeasurementArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Measurement $resource */
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
        /** @var Measurement $resource */
        /** @var MeasurementProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Measurement $resource */
        return $resource->{Measurement::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return MeasurementProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        return $this->measurementRepo->instance($input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Measurement $resource */

        $this->measurementRepo->fill($resource, $input);
        return $resource;
    }
}
