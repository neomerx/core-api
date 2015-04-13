<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Models\Tax;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\TaxRule;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Taxes\TaxRepositoryInterface;
use \Neomerx\Core\Repositories\Taxes\TaxRuleRepositoryInterface;

class TaxRules extends SingleResourceApi implements TaxRulesInterface
{
    const EVENT_PREFIX = 'Api.TaxRule.';
    const BIND_NAME    = __CLASS__;

    /**
     * @var TaxRuleRepositoryInterface
     */
    private $ruleRepo;

    /**
     * @var TaxRepositoryInterface
     */
    private $taxRepo;

//    protected static $relations = [
//        TaxRule::FIELD_TAX,
//        'territories.territory',
//        TaxRule::FIELD_POSTCODES,
//        'productTypes.type',
//        'customerTypes.type',
//    ];
//
//    /**
//     * @var TaxRule
//     */
//    private $ruleModel;
//
//    /**
//     * @var Country
//     */
//    private $countryModel;
//
//    /**
//     * @var Region
//     */
//    private $regionModel;
//
//    /**
//     * @var CustomerType
//     */
//    private $customerTypeModel;
//
//    /**
//     * @var ProductTaxType
//     */
//    private $productTypeModel;
//
//    /**
//     * @var Tax
//     */
//    private $taxModel;
//
//    /**
//     * @param TaxRule        $ruleModel
//     * @param Country        $countryModel
//     * @param Region         $regionModel
//     * @param CustomerType   $customerTypeModel
//     * @param ProductTaxType $productTypeModel
//     * @param Tax            $taxModel
//     */
//    public function __construct(
//        TaxRule $ruleModel,
//        Country $countryModel,
//        Region $regionModel,
//        CustomerType $customerTypeModel,
//        ProductTaxType $productTypeModel,
//        Tax $taxModel
//    ) {
//        $this->ruleModel         = $ruleModel;
//        $this->countryModel      = $countryModel;
//        $this->regionModel       = $regionModel;
//        $this->customerTypeModel = $customerTypeModel;
//        $this->productTypeModel  = $productTypeModel;
//        $this->taxModel          = $taxModel;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function create(array $input)
//    {
//        list($taxId, $ruleData, $territories, $postcodes, $customerTypes, $productTypes) =$this->parseTaxRule($input);
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        DB::beginTransaction();
//        try {
//
//            /** @var \Neomerx\Core\Models\TaxRule $rule */
//            /** @noinspection PhpUndefinedMethodInspection */
//            $rule = App::make(TaxRule::class);
//            $rule->fill($ruleData);
//            $rule->{Tax::FIELD_ID} = $taxId;
//            $rule->saveOrFail();
//            Permissions::check($rule, Permission::create());
//
//            $this->addRuleFilters($rule->{TaxRule::FIELD_ID},$territories, $postcodes, $customerTypes, $productTypes);
//
//            $allExecutedOk = true;
//
//        } finally {
//
//            /** @noinspection PhpUndefinedMethodInspection */
//            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
//
//        }
//
//        Event::fire(new TaxRuleArgs(self::EVENT_PREFIX . 'created', $rule));
//
//        return $rule;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function read($ruleId)
//    {
//
//        /** @var \Neomerx\Core\Models\TaxRule $resource */
//        $resource = $this->ruleModel->with(static::$relations)->findOrFail($ruleId);
//        Permissions::check($resource, Permission::view());
//        return $resource;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function update($ruleId, array $input)
//    {
//        list($taxId, $ruleData, $territories, $postcodes, $customerTypes, $productTypes) =$this->parseTaxRule($input);
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        DB::beginTransaction();
//        try {
//
//            /** @var \Neomerx\Core\Models\TaxRule $rule */
//            $rule = $this->ruleModel->with(static::$relations)->findOrFail($ruleId);
//            Permissions::check($rule, Permission::edit());
//            $rule->fill($ruleData);
//            $rule->{Tax::FIELD_ID} = $taxId;
//            $rule->saveOrFail();
//
//            // remove all rule filters for territories, postcodes, customer types and product tax types and ...
//            /** @noinspection PhpUndefinedMethodInspection */
//            $rule->territories()->delete();
//            /** @noinspection PhpUndefinedMethodInspection */
//            $rule->postcodes()->delete();
//            /** @noinspection PhpUndefinedMethodInspection */
//            $rule->customerTypes()->delete();
//            /** @noinspection PhpUndefinedMethodInspection */
//            $rule->productTypes()->delete();
//
//            // ... add new ones
//            $this->addRuleFilters($rule->{TaxRule::FIELD_ID}, $territories,$postcodes, $customerTypes, $productTypes);
//
//            $allExecutedOk = true;
//
//        } finally {
//
//            /** @noinspection PhpUndefinedMethodInspection */
//            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
//
//        }
//
//        Event::fire(new TaxRuleArgs(self::EVENT_PREFIX . 'updated', $rule));
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function delete($ruleId)
//    {
//        /** @var \Neomerx\Core\Models\TaxRule $resource */
//        $resource = $this->ruleModel->findOrFail($ruleId);
//        Permissions::check($resource, Permission::delete());
//        $resource->deleteOrFail();
//
//        Event::fire(new TaxRuleArgs(self::EVENT_PREFIX . 'deleted', $resource));
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function all()
//    {
//        $resources = $this->ruleModel->with(static::$relations)->get();
//
//        foreach ($resources as $resource) {
//            /** @var \Neomerx\Core\Models\TaxRule $resource */
//            Permissions::check($resource, Permission::view());
//        }
//
//        return $resources;
//    }
//
//    /**
//     * @param array $input
//     *
//     * @return array
//     */
//    private function parseTaxRule(
//        array $input
//    ) {
//        $taxCode = $this->readAndCheckNotEmpty($input, self::PARAM_TAX_CODE);
//
//        $priority = S\arrayGetValue($input, self::PARAM_PRIORITY);
//        (!empty($priority) and is_numeric($priority)) ?:S\throwEx(new InvalidArgumentException(self::PARAM_PRIORITY));
//
//        // every rule must have at least 1 record (e.g. '*' meaning no restrictions)
//        $territories   = $this->readAndCheckNotEmpty($input, self::$rulesTerritories);
//        $postcodes     = $this->readAndCheckNotEmpty($input, self::$rulesPostcodes);
//        $customerTypes = $this->readAndCheckNotEmpty($input, self::$rulesCustomerTypes);
//        $productTypes  = $this->readAndCheckNotEmpty($input, self::$rulesProductTypes);
//        unset($input[self::PARAM_TAX_CODE]);
//        unset($input[self::$rulesTerritories]);
//        unset($input[self::$rulesPostcodes]);
//        unset($input[self::$rulesCustomerTypes]);
//        unset($input[self::$rulesProductTypes]);
//
//        return [
//            $this->taxModel->selectByCode($taxCode)->firstOrFail([Tax::FIELD_ID])->{Tax::FIELD_ID},
//            $input,
//            $this->parseTerritories($this->countryModel, $this->regionModel, $territories),
//            $this->parsePostcodes($postcodes),
//            $this->parseCustomerTypes($this->customerTypeModel, $customerTypes),
//            $this->parseProductTypes($this->productTypeModel, $productTypes),
//        ];
//    }
//
//    /**
//     * @param array  $input
//     * @param string $key
//     *
//     * @return mixed
//     */
//    private function readAndCheckNotEmpty(array $input, $key)
//    {
//        $value = S\arrayGetValue($input, $key);
//        !empty($value) ?: S\throwEx(new InvalidArgumentException($key));
//        return $value;
//    }
//
//    /**
//     * @param int   $ruleId
//     * @param array $territories
//     * @param array $postcodes
//     * @param array $customerTypes
//     * @param array $productTypes
//     */
//    private function addRuleFilters(
//        $ruleId,
//        array $territories,
//        array $postcodes,
//        array $customerTypes,
//        array $productTypes
//    ) {
//        /** @var \Neomerx\Core\Models\TaxRuleTerritory $territory */
//        foreach ($territories as $territory) {
//            $territory->{TaxRule::FIELD_ID} = $ruleId;
//            $territory->saveOrFail();
//        }
//
//        /** @var \Neomerx\Core\Models\TaxRulePostcode $postcode */
//        foreach ($postcodes as $postcode) {
//            $postcode->{TaxRule::FIELD_ID} = $ruleId;
//            $postcode->saveOrFail();
//        }
//
//        /** @var \Neomerx\Core\Models\TaxRuleCustomerType $type */
//        foreach ($customerTypes as $type) {
//            $type->{TaxRule::FIELD_ID} = $ruleId;
//            $type->saveOrFail();
//        }
//
//        /** @var \Neomerx\Core\Models\TaxRuleProductType $type */
//        foreach ($productTypes as $type) {
//            $type->{TaxRule::FIELD_ID} = $ruleId;
//            $type->saveOrFail();
//        }
//    }

    /**
     * @param TaxRuleRepositoryInterface $ruleRepo
     * @param TaxRepositoryInterface     $taxRepo
     */
    public function __construct(TaxRuleRepositoryInterface $ruleRepo, TaxRepositoryInterface $taxRepo)
    {
        $this->ruleRepo = $ruleRepo;
        $this->taxRepo  = $taxRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->ruleRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            TaxRule::withTax(),
            TaxRule::withPostcodes(),
            TaxRule::withTerritories(),
            TaxRule::withProductTypes(),
            TaxRule::withCustomerTypes(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            TaxRule::FIELD_NAME       => SearchGrammar::TYPE_STRING,
            TaxRule::FIELD_PRIORITY   => SearchGrammar::TYPE_INT,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var TaxRule $resource */
        return new TaxRuleArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Tax $tax */
        $tax = $this->keyToModelEx($input, self::PARAM_TAX_CODE, $this->taxRepo);

        return $this->ruleRepo->instance($tax, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var TaxRule $resource */

        /** @var Tax $tax */
        $tax = $this->keyToModel($input, self::PARAM_TAX_CODE, $this->taxRepo);

        $this->ruleRepo->fill($resource, $tax, $input);
        return $resource;
    }
}
