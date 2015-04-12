<?php namespace Neomerx\CoreApi\Api\Manufacturers;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Manufacturer;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\ManufacturerProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\CoreApi\Api\Addresses\AddressesInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Manufacturers\ManufacturerRepositoryInterface;
use \Neomerx\Core\Repositories\Manufacturers\ManufacturerPropertiesRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Manufacturers extends ResourceWithPropertiesApi implements ManufacturersInterface
{
    const EVENT_PREFIX = 'Api.Manufacturer.';
    const BIND_NAME    = __CLASS__;

    /**
     * @var ManufacturerRepositoryInterface
     */
    private $manufacturerRepo;

    /**
     * @var ManufacturerPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @var AddressesInterface
     */
    private $addressApi;

    /**
     * @param ManufacturerRepositoryInterface           $manufacturerRepo
     * @param ManufacturerPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface               $languageRepo
     * @param AddressesInterface                        $addressRepo
     */
    public function __construct(
        ManufacturerRepositoryInterface $manufacturerRepo,
        ManufacturerPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo,
        AddressesInterface $addressRepo
    ) {
        parent::__construct($languageRepo);
        $this->manufacturerRepo = $manufacturerRepo;
        $this->propertiesRepo   = $propertiesRepo;
        $this->addressApi       = $addressRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->manufacturerRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Manufacturer::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Manufacturer::FIELD_CODE  => SearchGrammar::TYPE_STRING,
            'created'                 => [SearchGrammar::TYPE_DATE, Manufacturer::FIELD_CREATED_AT],
            'updated'                 => [SearchGrammar::TYPE_DATE, Manufacturer::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Manufacturer $resource */
        return new ManufacturerArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Manufacturer $resource */
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
        /** @var Manufacturer $resource */
        /** @var ManufacturerProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Manufacturer $resource */
        return $resource->{Manufacturer::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return ManufacturerProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        $address = $this->addressApi->create(S\arrayGetValueEx($input, self::PARAM_ADDRESS));
        return $this->manufacturerRepo->instance($address, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Manufacturer $resource */

        $addressInput = S\arrayGetValue($input, self::PARAM_ADDRESS);
        if (empty($addressInput) === false) {
            $this->addressApi->update($resource->{Manufacturer::FIELD_ID_ADDRESS}, $addressInput);
        }
        $this->manufacturerRepo->fill($resource, null, $input);
        return $resource;
    }
}
