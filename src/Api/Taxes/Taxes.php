<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Config;
use \Neomerx\Core\Models\Tax;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Region;
use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Models\Country;
use \Neomerx\Core\Models\Customer;
use \Neomerx\Core\Models\BaseModel;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Cart\CartItem;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\CoreApi\Api\Carriers\Tariff;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\CoreApi\Api\Carriers\ShippingData;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Converters\ConverterInterface;
use \Neomerx\CoreApi\Converters\VariantConverterGeneric;
use \Neomerx\CoreApi\Converters\AddressConverterGeneric;
use \Neomerx\CoreApi\Converters\CustomerConverterGeneric;
use \Neomerx\Core\Repositories\Taxes\TaxRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Taxes extends SingleResourceApi implements TaxesInterface
{
    const EVENT_PREFIX = 'Api.Tax.';

    /**
     * @var TaxRepositoryInterface
     */
    private $taxRepo;

    /**
     * @param TaxRepositoryInterface $taxRepo
     */
    public function __construct(TaxRepositoryInterface $taxRepo)
    {
        $this->taxRepo = $taxRepo;
    }

    /**
     * @return TaxRepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->taxRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Tax::FIELD_CODE           => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        return $this->taxRepo->instance($input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Tax $resource */

        $this->taxRepo->fill($resource, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Tax $resource */
        return new TaxArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    public function getTaxes(Address $address, CustomerType $customerType, ProductTaxType $productTaxType)
    {
        $region = $address->{Address::FIELD_REGION};
        return $this->taxRepo->selectTaxes(
            $region->{Country::FIELD_ID},
            $region->{Region::FIELD_ID},
            $address->postcode,
            $customerType->{CustomerType::FIELD_ID},
            $productTaxType->{ProductTaxType::FIELD_ID}
        );
    }

    /**
     * @inheritdoc
     */
    public function calculateTax(ShippingData $shippingData, Tariff $shipping)
    {
        $addressTo = (ShippingData::TYPE_PICKUP === $shippingData->getShippingType() ?
            $shippingData->getPickupStore()->address : $shippingData->getAddressTo());

        // TODO Sort out the mess with admin and core configs
        $taxAddress = Config::get(Config::KEY_TAX_ADDRESS_USE_FROM_INSTEADOF_TO) === true ?
            $shippingData->getAddressFrom() : $addressTo;

        $customer       = $shippingData->getCustomer();
        $countryId      = $taxAddress->region->{Region::FIELD_ID_COUNTRY};
        $regionId       = $taxAddress->{Address::FIELD_ID_REGION};
        $postcode       = $taxAddress->{Address::FIELD_POSTCODE};
        $customerTypeId = $customer->{Customer::FIELD_ID_CUSTOMER_TYPE};

        $taxDetails      = [];
        $totalOrderTaxes = 0;
        $addressFrom     = $shippingData->getAddressFrom();

        // format objects for tax calculation formula
        /** @noinspection PhpUndefinedMethodInspection */
        $taxParameters   = [
            Tax::PARAM_CUSTOMER     => (object)App::make(CustomerConverterGeneric::class)->convert($customer),
            Tax::PARAM_ADDRESS_TO   => (object)App::make(AddressConverterGeneric::class)->convert($addressTo),
            Tax::PARAM_ADDRESS_FROM => (object)App::make(AddressConverterGeneric::class)->convert($addressFrom),
        ];

        /** @var ConverterInterface $variantConverter */
        /** @noinspection PhpUndefinedMethodInspection */
        $variantConverter  = App::make(VariantConverterGeneric::class);

        /** @var CartItem $cartItem */
        foreach ($shippingData->getCart() as $cartItem) {
            $currentVariant = $cartItem->getVariant();

            $taxes = $this->taxRepo->selectTaxes(
                $countryId,
                $regionId,
                $postcode,
                $customerTypeId,
                $currentVariant->product->{ProductTaxType::FIELD_ID}
            );

            $this->sumTaxes($taxes, array_merge($taxParameters, [
                Tax::PARAM_PRODUCT  => (object)$variantConverter->convert($currentVariant),
                Tax::PARAM_PRICE    => $currentVariant->price_wo_tax,
                Tax::PARAM_QUANTITY => $cartItem->getQuantity(),
            ]), $totalOrderTaxes, $taxDetails);
        }

        // calculate shipping cost
        $taxOnShipping = 0;
        $shippingTaxes = $this->taxRepo->selectTaxes(
            $countryId,
            $regionId,
            $postcode,
            $customerTypeId,
            ProductTaxType::SHIPPING_ID
        );
        $this->sumTaxes($shippingTaxes, array_merge($taxParameters, [
            Tax::PARAM_PRICE    => $shipping->getCost(),
            Tax::PARAM_QUANTITY => 1,
        ]), $taxOnShipping, $taxDetails);

        return new TaxCalculation($totalOrderTaxes, array_values($taxDetails), $taxOnShipping);
    }

    /**
     * @param Collection $taxes
     * @param array      $parameters
     * @param float      $total
     * @param array      $details
     */
    private function sumTaxes(Collection $taxes, array $parameters, &$total, array &$details)
    {
        $totalItemTaxes = 0;
        /** @var \Neomerx\Core\Models\Tax $tax */
        foreach ($taxes as $tax) {
            $itemTax = $tax->calculate(
                array_merge($parameters, [Tax::PARAM_CUMULATIVE_TAX => $totalItemTaxes])
            );
            $totalItemTaxes += $itemTax;
            $taxCode = $tax->code;
            if (array_key_exists($taxCode, $details)) {
                $details[$taxCode][1] += $itemTax;
            } else {
                $details[$taxCode] = [$tax->attributesToArray(), $itemTax];
            }
        }

        $total += $totalItemTaxes;
    }
}
