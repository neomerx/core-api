<?php namespace Neomerx\CoreApi\Api\Suppliers;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Supplier;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\SupplierProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\CoreApi\Api\Addresses\AddressesInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Suppliers\SupplierRepositoryInterface;
use \Neomerx\Core\Repositories\Suppliers\SupplierPropertiesRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Suppliers extends ResourceWithPropertiesApi implements SuppliersInterface
{
    const EVENT_PREFIX = 'Api.Supplier.';

//    /**
//     * @var Supplier
//     */
//    private $supplier;
//
//    /**
//     * @var SupplierProperties
//     */
//    private $properties;
//
//    /**
//     * @var Addresses
//     */
//    private $addressApi;
//
//    /**
//     * @var Language
//     */
//    private $language;
//
//    /**
//     * Searchable fields of the resource.
//     * Could be used as parameters in search function.
//     *
//     * @var array
//     */
//    protected static $searchRules = [
//        Supplier::FIELD_CODE      => SearchGrammar::TYPE_STRING,
//        'created'                 => [SearchGrammar::TYPE_DATE, Supplier::FIELD_CREATED_AT],
//        'updated'                 => [SearchGrammar::TYPE_DATE, Supplier::FIELD_UPDATED_AT],
//        SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
//        SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
//    ];
//
//    /**
//     * @param Supplier           $supplier
//     * @param SupplierProperties $properties
//     * @param Addresses          $addressApi
//     * @param Language           $language
//     */
//    public function __construct(
//        Supplier $supplier,
//        SupplierProperties $properties,
//        Addresses $addressApi,
//        Language $language
//    ) {
//        $this->supplier      = $supplier;
//        $this->properties = $properties;
//        $this->addressApi = $addressApi;
//        $this->language   = $language;
//    }
//
//    /**
//     * {@inheritdoc}
//     *
//     * @SuppressWarnings(PHPMD.NPathComplexity)
//     */
//    public function create(array $input)
//    {
//        list($input, $propertiesInput) = $this->extractPropertiesInput($this->language, $input);
//        !empty($propertiesInput) ?: S\throwEx(new InvalidArgumentException(Supplier::FIELD_PROPERTIES));
//
//        $addressInput = S\arrayGetValue($input, self::PARAM_ADDRESS);
//        !empty($addressInput) ?: S\throwEx(new InvalidArgumentException(self::PARAM_ADDRESS));
//        unset($input[self::PARAM_ADDRESS]);
//
//        $supplier = null;
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        DB::beginTransaction();
//        try {
//
//            // create address, update input and add resource
//            $input[Address::FIELD_ID] = $this->addressApi->create($addressInput)->{Address::FIELD_ID};
//            /** @var \Neomerx\Core\Models\Supplier $supplier */
//            $supplier = $this->supplier->createOrFailResource($input);
//            Permissions::check($supplier, Permission::create());
//            $supplierId = $supplier->{Supplier::FIELD_ID};
//            foreach ($propertiesInput as $languageId => $propertyInput) {
//                $this->properties->createOrFail(array_merge($propertyInput, [
//                    SupplierProperties::FIELD_ID_SUPPLIER => $supplierId,
//                    SupplierProperties::FIELD_ID_LANGUAGE => $languageId
//                ]));
//            }
//
//            $allExecutedOk = true;
//
//        } finally {
//            /** @noinspection PhpUndefinedMethodInspection */
//            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
//        }
//
//        if ($supplier !== null) {
//            Event::fire(new SupplierArgs(self::EVENT_PREFIX . 'created', $supplier));
//        }
//
//        return $supplier;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function read($code)
//    {
//        /** @var \Neomerx\Core\Models\Supplier $supplier */
//        /** @noinspection PhpUndefinedMethodInspection */
//        $supplier = $this->supplier->selectByCode($code)->withAddress()->withProperties()->firstOrFail();
//        Permissions::check($supplier, Permission::view());
//        return $supplier;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function update($code, array $input)
//    {
//        // get input for properties
//        list($input, $propertiesInput) = $this->extractPropertiesInput($this->language, $input);
//
//        $addressInput = S\arrayGetValue($input, self::PARAM_ADDRESS);
//        unset($input[self::PARAM_ADDRESS]);
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        DB::beginTransaction();
//        try {
//            // update resource
//            /** @var \Neomerx\Core\Models\Supplier $supplier */
//            $supplier = $this->supplier->selectByCode($code)->firstOrFail();
//            // always check supplier because later we update its properties
//            Permissions::check($supplier, Permission::edit());
//            if (!empty($input)) {
//                $supplier->updateOrFail($input);
//            }
//
//            // update address
//            if (!empty($addressInput)) {
//                $this->addressApi->updateModel($supplier->address, $addressInput);
//            }
//
//            // update language properties
//            $resourceId = $supplier->{Supplier::FIELD_ID};
//            foreach ($propertiesInput as $languageId => $propertyInput) {
//                /** @var SupplierProperties $property */
//                $property = $this->properties->updateOrCreate(
//                    [
//                        SupplierProperties::FIELD_ID_SUPPLIER => $resourceId,
//                        SupplierProperties::FIELD_ID_LANGUAGE => $languageId
//                    ],
//                    $propertyInput
//                );
//                $property->exists ?: S\throwEx(new ValidationException($property->getValidator()));
//            }
//
//            $allExecutedOk = true;
//
//        } finally {
//            /** @noinspection PhpUndefinedMethodInspection */
//            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
//        }
//
//        Event::fire(new SupplierArgs(self::EVENT_PREFIX . 'updated', $supplier));
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function delete($code)
//    {
//        /** @var \Neomerx\Core\Models\Supplier $supplier */
//        $supplier = $this->supplier->selectByCode($code)->firstOrFail();
//
//        Permissions::check($supplier, Permission::delete());
//
//        $supplier->deleteOrFail();
//
//        Event::fire(new SupplierArgs(self::EVENT_PREFIX . 'deleted', $supplier));
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function search(array $parameters = [])
//    {
//        /** @noinspection PhpUndefinedMethodInspection */
//        $builder = $this->supplier->newQuery()->withAddress()->withProperties();
//
//        // add search parameters if required
//        if (!empty($parameters)) {
//            $parser  = new SearchParser(new SearchGrammar($builder), static::$searchRules);
//            $builder = $parser->buildQuery($parameters);
//        }
//
//        $suppliers = $builder->get();
//
//        foreach ($suppliers as $supplier) {
//            /** @var \Neomerx\Core\Models\Supplier $supplier */
//            Permissions::check($supplier, Permission::view());
//        }
//
//        return $suppliers;
//    }

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
        $address = $this->addressApi->create(S\arrayGetValueEx($input, self::PARAM_ADDRESS));
        return $this->supplierRepo->instance($address, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Supplier $resource */

        $addressInput = S\arrayGetValue($input, self::PARAM_ADDRESS);
        if (empty($addressInput) === false) {
            $this->addressApi->update($resource->{Supplier::FIELD_ID_ADDRESS}, $addressInput);
        }
        $this->supplierRepo->fill($resource, null, $input);
        return $resource;
    }
}
