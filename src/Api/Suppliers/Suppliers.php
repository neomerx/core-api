<?php namespace Neomerx\CoreApi\Api\Suppliers;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\Supplier;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\SupplierProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\CoreApi\Api\Addresses\AddressesInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Suppliers\SupplierRepositoryInterface;
use \Neomerx\Core\Repositories\Suppliers\SupplierPropertiesRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Suppliers extends ResourceWithPropertiesApi implements SuppliersInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.Supplier.';

    /**
     * @var SupplierRepositoryInterface
     */
    private $supplierRepo;

    /**
     * @var SupplierPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @var AddressesInterface
     */
    private $addressApi;

    /**
     * @param SupplierRepositoryInterface           $supplierRepo
     * @param SupplierPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface           $languageRepo
     * @param AddressesInterface                    $addressRepo
     */
    public function __construct(
        SupplierRepositoryInterface $supplierRepo,
        SupplierPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo,
        AddressesInterface $addressRepo
    ) {
        parent::__construct($languageRepo);
        $this->supplierRepo   = $supplierRepo;
        $this->propertiesRepo = $propertiesRepo;
        $this->addressApi     = $addressRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->supplierRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Supplier::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Supplier::FIELD_CODE      => SearchGrammar::TYPE_STRING,
            'created'                 => [SearchGrammar::TYPE_DATE, Supplier::FIELD_CREATED_AT],
            'updated'                 => [SearchGrammar::TYPE_DATE, Supplier::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Supplier $resource */
        return new SupplierArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Supplier $resource */
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
        /** @var Supplier $resource */
        /** @var SupplierProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Supplier $resource */
        return $resource->{Supplier::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return SupplierProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        $address = $this->addressApi->read(S\arrayGetValueEx($input, self::PARAM_ID_ADDRESS));
        return $this->supplierRepo->instance($address, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Supplier $resource */

        $addressId = S\arrayGetValue($input, self::PARAM_ID_ADDRESS);
        $address   = $addressId !== null ? $this->addressApi->read($addressId) : null;

        $this->supplierRepo->fill($resource, $address, $input);
        return $resource;
    }
}
