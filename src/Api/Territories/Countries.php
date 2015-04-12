<?php namespace Neomerx\CoreApi\Api\Territories;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Country;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Auth\Permission;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Auth\Facades\Permissions;
use \Neomerx\Core\Models\CountryProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\CountryRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\CountryPropertiesRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Countries extends ResourceWithPropertiesApi implements CountriesInterface
{
    const EVENT_PREFIX = 'Api.Country.';
    const BIND_NAME    = __CLASS__;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepo;

    /**
     * @var CountryPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @param CountryRepositoryInterface           $countryRepo
     * @param CountryPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface          $languageRepo
     */
    public function __construct(
        CountryRepositoryInterface $countryRepo,
        CountryPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo
    ) {
        parent::__construct($languageRepo);
        $this->countryRepo    = $countryRepo;
        $this->propertiesRepo = $propertiesRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->countryRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Country::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Country::FIELD_CODE       => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Country $resource */
        return new CountryArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Country $resource */
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
        /** @var Country $resource */
        /** @var CountryProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Country $resource */
        return $resource->{Country::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return CountryProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        return $this->countryRepo->instance($input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Country $resource */
        $this->countryRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    public function regions($countryCode)
    {
        /** @var Country $country */
        $country = $this->read($countryCode);
        return $this->regionsByCountry($country);
    }

    /**
     * @inheritdoc
     */
    public function regionsByCountry(Country $country)
    {
        Permissions::check($country, Permission::view());
        $regions = $this->countryRepo->regions($country);
        foreach ($regions as $region) {
            Permissions::check($region, Permission::view());
        }
        return $regions;
    }
}
