<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\CoreApi\Support\Config;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\CarrierProperties;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Carriers\CarrierRepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Carriers\CarrierPropertiesRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Carriers extends ResourceWithPropertiesApi implements CarriersInterface
{
    const EVENT_PREFIX = 'Api.Carrier.';

    /**
     * @var CarrierRepositoryInterface
     */
    private $carrierRepo;

    /**
     * @var CarrierPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @param CarrierRepositoryInterface           $carrierRepo
     * @param CarrierPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface          $languageRepo
     */
    public function __construct(
        CarrierRepositoryInterface $carrierRepo,
        CarrierPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo
    ) {
        parent::__construct($languageRepo);
        $this->carrierRepo    = $carrierRepo;
        $this->propertiesRepo = $propertiesRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->carrierRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Carrier::withProperties()];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Carrier::FIELD_CODE          => SearchGrammar::TYPE_STRING,
            Carrier::FIELD_IS_TAXABLE    => SearchGrammar::TYPE_BOOL,
            Carrier::FIELD_MIN_WEIGHT    => SearchGrammar::TYPE_FLOAT,
            Carrier::FIELD_MAX_WEIGHT    => SearchGrammar::TYPE_FLOAT,
            Carrier::FIELD_MIN_COST      => SearchGrammar::TYPE_FLOAT,
            Carrier::FIELD_MAX_COST      => SearchGrammar::TYPE_FLOAT,
            Carrier::FIELD_MIN_DIMENSION => SearchGrammar::TYPE_FLOAT,
            Carrier::FIELD_MAX_DIMENSION => SearchGrammar::TYPE_FLOAT,
            SearchGrammar::LIMIT_SKIP    => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE    => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Carrier $resource */
        return new CarrierArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Carrier $resource */
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
        /** @var Carrier $resource */
        /** @var CarrierProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Carrier $resource */
        return $resource->{Carrier::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return CarrierProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        return $this->carrierRepo->instance($input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Carrier $resource */

        $this->carrierRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * Select all carriers matching address, dimension and price limitations in $shippingData and calculate tariffs.
     *
     * @param ShippingData $shippingData
     *
     * @return array [..., [Carrier, Tariff], ...]
     */
    public function calculateTariffs(ShippingData $shippingData)
    {
        $tariffs = [];

        /** @var \Neomerx\Core\Models\Carrier $carrier */
        foreach ($this->selectCarriers($shippingData) as $carrier) {
            $tariffs[] = [$carrier, $this->calculateTariff($shippingData, $carrier)];
        }

        return $tariffs;
    }

    /**
     * Calculate tariff for specified carrier.
     *
     * @param ShippingData $shippingData
     * @param Carrier      $carrier
     *
     * @return Tariff
     */
    public function calculateTariff(ShippingData $shippingData, Carrier $carrier)
    {
        $calculatorCode     = $carrier->{Carrier::FIELD_CALCULATOR_CODE};
        $settingsKey        = Config::KEY_CARRIERS.'.'.Config::KEY_CARRIERS_TARIFF_CALCULATORS.'.'.$calculatorCode;
        $calculatorSettings = Config::get($settingsKey);
        /** @var TariffCalculatorInterface $calculator */
        $calculator = app($calculatorSettings[Config::PARAM_CALCULATOR_FACTORY]);
        assert('$calculator instanceof '.TariffCalculatorInterface::class);

        $calculator->init($carrier);
        return $calculator->calculate(TariffCalculatorData::newFromShippingData($carrier->data, $shippingData));
    }

    /**
     * @param ShippingData $shippingData
     *
     * @return Collection
     */
    private function selectCarriers(ShippingData $shippingData)
    {
        $region = $shippingData->getAddressTo()->region;

        return $this->carrierRepo->selectCarriers(
            $region->id_country,
            $region->id_region,
            $shippingData->getAddressTo()->postcode,
            $shippingData->getCustomer()->id_customer_type,
            $shippingData->getCart()->getWeight(),
            $shippingData->getCart()->getMaxDimension(),
            $shippingData->getCart()->getPriceWoTax()
        );
    }

    /**
     * Get available carrier calculators.
     *
     * @return array
     */
    public function getAvailableCalculators()
    {
        $result = [];
        foreach (Config::get(Config::KEY_CARRIERS.'.'.Config::KEY_CARRIERS_TARIFF_CALCULATORS) as $code => $setup) {
            assert('isset($setup[\''.Config::PARAM_CALCULATOR_NAME.'\'])');
            assert('isset($setup[\''.Config::PARAM_CALCULATOR_DESCRIPTION.'\'])');

            $result[] = [
                Config::PARAM_CALCULATOR_CODE        => $code,
                Config::PARAM_CALCULATOR_NAME        => trans($setup[Config::PARAM_CALCULATOR_NAME]),
                Config::PARAM_CALCULATOR_DESCRIPTION => trans($setup[Config::PARAM_CALCULATOR_DESCRIPTION]),
            ];
        }
        return $result;
    }
}
