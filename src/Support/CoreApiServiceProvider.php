<?php namespace Neomerx\CoreApi\Support;

use \Auth;
use \Neomerx\CoreApi\Api\Auth\Roles;
use \Neomerx\CoreApi\Api\Taxes\Taxes;
use \Neomerx\CoreApi\Api\Images\Images;
use \Neomerx\CoreApi\Api\Orders\Orders;
use \Neomerx\CoreApi\Api\Stores\Stores;
use \Neomerx\CoreApi\Api\Taxes\TaxRules;
use \Illuminate\Support\ServiceProvider;
use \Neomerx\CoreApi\Api\Auth\ObjectTypes;
use \Neomerx\CoreApi\Api\Products\Related;
use \Neomerx\CoreApi\Api\Carriers\Carriers;
use \Neomerx\CoreApi\Api\Products\Products;
use \Neomerx\CoreApi\Api\Products\Variants;
use \Neomerx\CoreApi\Api\Auth\EmployeeLogin;
use \Neomerx\CoreApi\Api\Addresses\Addresses;
use \Neomerx\CoreApi\Api\Customers\Customers;
use \Neomerx\CoreApi\Api\Employees\Employees;
use \Neomerx\CoreApi\Api\Images\ImageFormats;
use \Neomerx\CoreApi\Api\Languages\Languages;
use \Neomerx\CoreApi\Api\Suppliers\Suppliers;
use \Neomerx\CoreApi\Api\Territories\Regions;
use \Neomerx\CoreApi\Api\Auth\RoleObjectTypes;
use \Neomerx\CoreApi\Api\Orders\OrderStatuses;
use \Neomerx\CoreApi\Api\Categories\Categories;
use \Neomerx\CoreApi\Api\Currencies\Currencies;
use \Neomerx\CoreApi\Api\Features\Measurements;
use \Neomerx\CoreApi\Api\Territories\Countries;
use \Neomerx\CoreApi\Api\Inventory\Inventories;
use \Neomerx\CoreApi\Api\Warehouses\Warehouses;
use \Neomerx\Core\Auth\Token\CacheTokenManager;
use \Neomerx\CoreApi\Api\Features\FeatureValues;
use \Neomerx\CoreApi\Api\Products\ProductImages;
use \Neomerx\CoreApi\Api\Taxes\TaxRulePostcodes;
use \Neomerx\CoreApi\Api\Customers\CustomerRisks;
use \Neomerx\CoreApi\Api\Customers\CustomerTypes;
use \Neomerx\CoreApi\Api\Employees\EmployeeRoles;
use \Neomerx\CoreApi\Api\Products\Specifications;
use \Illuminate\Contracts\Foundation\Application;
use \Neomerx\CoreApi\Api\Features\Characteristics;
use \Neomerx\CoreApi\Api\Products\ProductTaxTypes;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleTerritories;
use \Neomerx\Core\Auth\PermissionManagerInterface;
use \Neomerx\CoreApi\Api\Carriers\CarrierPostcodes;
use \Neomerx\CoreApi\Api\Facades\Roles as RolesApi;
use \Neomerx\CoreApi\Api\Facades\Taxes as TaxesApi;
use \Neomerx\CoreApi\Api\SupplyOrders\SupplyOrders;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleProductTypes;
use \Neomerx\Core\Auth\Token\TokenManagerInterface;
use \Neomerx\CoreApi\Api\Products\ProductCategories;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleCustomerTypes;
use \Neomerx\Core\Auth\Token\CachePermissionManager;
use \Neomerx\CoreApi\Api\Carriers\CarrierTerritories;
use \Neomerx\CoreApi\Api\Facades\Images as ImagesApi;
use \Neomerx\CoreApi\Api\Facades\Orders as OrdersApi;
use \Neomerx\CoreApi\Api\Facades\Stores as StoresApi;
use \Neomerx\CoreApi\Api\Customers\CustomerAddresses;
use \Neomerx\CoreApi\Api\Manufacturers\Manufacturers;
use \Neomerx\CoreApi\Support\Config as CoreApiConfig;
use \Illuminate\Contracts\Auth\Guard as AuthInterface;
use \Neomerx\CoreApi\Api\Carriers\CarrierCustomerTypes;
use \Neomerx\CoreApi\Api\Facades\Regions as RegionsApi;
use \Neomerx\CoreApi\Api\Facades\Related as RelatedApi;
use \Neomerx\CoreApi\Api\ShippingOrders\ShippingOrders;
use \Neomerx\CoreApi\Api\Facades\Carriers as CarriersApi;
use \Neomerx\CoreApi\Api\Facades\Products as ProductsApi;
use \Neomerx\CoreApi\Api\Facades\TaxRules as TaxRulesApi;
use \Neomerx\CoreApi\Api\Facades\Variants as VariantsApi;
use \Neomerx\CoreApi\Api\ShippingOrders\ShippingStatuses;
use \Neomerx\CoreApi\Api\SupplyOrders\SupplyOrderDetails;
use \Neomerx\CoreApi\Api\Facades\Addresses as AddressesApi;
use \Neomerx\CoreApi\Api\Facades\Countries as CountriesApi;
use \Neomerx\CoreApi\Api\Facades\Customers as CustomersApi;
use \Neomerx\CoreApi\Api\Facades\Employees as EmployeesApi;
use \Neomerx\CoreApi\Api\Facades\Languages as LanguagesApi;
use \Neomerx\CoreApi\Api\Facades\Suppliers as SuppliersApi;
use \Neomerx\CoreApi\Server\Http\Auth\EmployeeUserProvider;
use \Neomerx\CoreApi\Api\Facades\Categories as CategoriesApi;
use \Neomerx\CoreApi\Api\Facades\Currencies as CurrenciesApi;
use \Neomerx\CoreApi\Api\Facades\Warehouses as WarehousesApi;
use \Illuminate\Contracts\Cache\Repository as CacheInterface;
use \Neomerx\CoreApi\Api\Facades\Inventories as InventoriesApi;
use \Neomerx\CoreApi\Api\Facades\ObjectTypes as ObjectTypesApi;
use \Neomerx\CoreApi\Api\Facades\ImageFormats as ImageFormatsApi;
use \Neomerx\CoreApi\Api\Facades\Measurements as MeasurementsApi;
use \Neomerx\CoreApi\Api\Facades\SupplyOrders as SupplyOrdersApi;
use \Neomerx\CoreApi\Api\Facades\CustomerRisks as CustomerRisksApi;
use \Neomerx\CoreApi\Api\Facades\CustomerTypes as CustomerTypesApi;
use \Neomerx\CoreApi\Api\Facades\EmployeeLogin as EmployeeLoginApi;
use \Neomerx\CoreApi\Api\Facades\EmployeeRoles as EmployeeRolesApi;
use \Neomerx\CoreApi\Api\Facades\FeatureValues as FeatureValuesApi;
use \Neomerx\CoreApi\Api\Facades\Manufacturers as ManufacturersApi;
use \Neomerx\CoreApi\Api\Facades\OrderStatuses as OrderStatusesApi;
use \Neomerx\CoreApi\Api\Facades\ProductImages as ProductImagesApi;
use \Illuminate\Contracts\Container\Container as ContainerInterface;
use \Neomerx\CoreApi\Api\Facades\ShippingOrders as ShippingOrdersApi;
use \Neomerx\CoreApi\Api\Facades\Specifications as SpecificationsApi;
use \Neomerx\CoreApi\Api\Facades\Characteristics as CharacteristicsApi;
use \Neomerx\CoreApi\Api\Facades\ProductTaxTypes as ProductTaxTypesApi;
use \Neomerx\CoreApi\Api\Facades\RoleObjectTypes as RoleObjectTypesApi;
use \Neomerx\CoreApi\Api\Facades\CarrierPostcodes as CarrierPostcodesApi;
use \Neomerx\CoreApi\Api\Facades\TaxRulePostcodes as TaxRulePostcodesApi;
use \Neomerx\CoreApi\Api\Facades\ShippingStatuses as ShippingStatusesApi;
use \Neomerx\CoreApi\Api\Facades\CustomerAddresses as CustomerAddressesApi;
use \Neomerx\CoreApi\Api\Facades\ProductCategories as ProductCategoriesApi;
use \Neomerx\CoreApi\Api\Facades\SupplyOrderDetails as SupplyOrderDetailsApi;
use \Neomerx\CoreApi\Api\Facades\CarrierTerritories as CarrierTerritoriesApi;
use \Neomerx\CoreApi\Api\Facades\TaxRuleTerritories as TaxRuleTerritoriesApi;
use \Neomerx\CoreApi\Api\Facades\TaxRuleProductTypes as TaxRuleProductTypesApi;
use \Neomerx\CoreApi\Api\Facades\CarrierCustomerTypes as CarrierCustomerTypesApi;
use \Neomerx\CoreApi\Api\Facades\TaxRuleCustomerTypes as TaxRuleCustomerTypesApi;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CoreApiServiceProvider extends ServiceProvider
{
    const PACKAGE_NAMESPACE = 'nm-core-api';

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @inheritdoc
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->rootDir = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
    }

    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->declareResources();

        // register auth user providers
        Auth::extend(EmployeeUserProvider::PROVIDER_NAME, function () {
            return app(EmployeeUserProvider::class);
        });
    }

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            $this->rootDir.'config'.DIRECTORY_SEPARATOR.'config.php',
            CoreApiConfig::CONFIG_FILE_NAME_WO_EXT
        );

        $this->registerAuth();

        $this->app->bind(RolesApi::INTERFACE_BIND_NAME, Roles::class);
        $this->app->bind(TaxesApi::INTERFACE_BIND_NAME, Taxes::class);
        $this->app->bind(ImagesApi::INTERFACE_BIND_NAME, Images::class);
        $this->app->bind(OrdersApi::INTERFACE_BIND_NAME, Orders::class);
        $this->app->bind(StoresApi::INTERFACE_BIND_NAME, Stores::class);
        $this->app->bind(RelatedApi::INTERFACE_BIND_NAME, Related::class);
        $this->app->bind(RegionsApi::INTERFACE_BIND_NAME, Regions::class);
        $this->app->bind(CarriersApi::INTERFACE_BIND_NAME, Carriers::class);
        $this->app->bind(ProductsApi::INTERFACE_BIND_NAME, Products::class);
        $this->app->bind(TaxRulesApi::INTERFACE_BIND_NAME, TaxRules::class);
        $this->app->bind(VariantsApi::INTERFACE_BIND_NAME, Variants::class);
        $this->app->bind(AddressesApi::INTERFACE_BIND_NAME, Addresses::class);
        $this->app->bind(CountriesApi::INTERFACE_BIND_NAME, Countries::class);
        $this->app->bind(CustomersApi::INTERFACE_BIND_NAME, Customers::class);
        $this->app->bind(EmployeesApi::INTERFACE_BIND_NAME, Employees::class);
        $this->app->bind(LanguagesApi::INTERFACE_BIND_NAME, Languages::class);
        $this->app->bind(SuppliersApi::INTERFACE_BIND_NAME, Suppliers::class);
        $this->app->bind(CategoriesApi::INTERFACE_BIND_NAME, Categories::class);
        $this->app->bind(CurrenciesApi::INTERFACE_BIND_NAME, Currencies::class);
        $this->app->bind(WarehousesApi::INTERFACE_BIND_NAME, Warehouses::class);
        $this->app->bind(InventoriesApi::INTERFACE_BIND_NAME, Inventories::class);
        $this->app->bind(ObjectTypesApi::INTERFACE_BIND_NAME, ObjectTypes::class);
        $this->app->bind(MeasurementsApi::INTERFACE_BIND_NAME, Measurements::class);
        $this->app->bind(ImageFormatsApi::INTERFACE_BIND_NAME, ImageFormats::class);
        $this->app->bind(SupplyOrdersApi::INTERFACE_BIND_NAME, SupplyOrders::class);
        $this->app->bind(CustomerRisksApi::INTERFACE_BIND_NAME, CustomerRisks::class);
        $this->app->bind(CustomerTypesApi::INTERFACE_BIND_NAME, CustomerTypes::class);
        $this->app->bind(EmployeeLoginApi::INTERFACE_BIND_NAME, EmployeeLogin::class);
        $this->app->bind(EmployeeRolesApi::INTERFACE_BIND_NAME, EmployeeRoles::class);
        $this->app->bind(FeatureValuesApi::INTERFACE_BIND_NAME, FeatureValues::class);
        $this->app->bind(ManufacturersApi::INTERFACE_BIND_NAME, Manufacturers::class);
        $this->app->bind(OrderStatusesApi::INTERFACE_BIND_NAME, OrderStatuses::class);
        $this->app->bind(ProductImagesApi::INTERFACE_BIND_NAME, ProductImages::class);
        $this->app->bind(ShippingOrdersApi::INTERFACE_BIND_NAME, ShippingOrders::class);
        $this->app->bind(SpecificationsApi::INTERFACE_BIND_NAME, Specifications::class);
        $this->app->bind(CharacteristicsApi::INTERFACE_BIND_NAME, Characteristics::class);
        $this->app->bind(ProductTaxTypesApi::INTERFACE_BIND_NAME, ProductTaxTypes::class);
        $this->app->bind(RoleObjectTypesApi::INTERFACE_BIND_NAME, RoleObjectTypes::class);
        $this->app->bind(ShippingStatusesApi::INTERFACE_BIND_NAME, ShippingStatuses::class);
        $this->app->bind(CarrierPostcodesApi::INTERFACE_BIND_NAME, CarrierPostcodes::class);
        $this->app->bind(TaxRulePostcodesApi::INTERFACE_BIND_NAME, TaxRulePostcodes::class);
        $this->app->bind(CustomerAddressesApi::INTERFACE_BIND_NAME, CustomerAddresses::class);
        $this->app->bind(ProductCategoriesApi::INTERFACE_BIND_NAME, ProductCategories::class);
        $this->app->bind(CarrierTerritoriesApi::INTERFACE_BIND_NAME, CarrierTerritories::class);
        $this->app->bind(SupplyOrderDetailsApi::INTERFACE_BIND_NAME, SupplyOrderDetails::class);
        $this->app->bind(TaxRuleTerritoriesApi::INTERFACE_BIND_NAME, TaxRuleTerritories::class);
        $this->app->bind(TaxRuleProductTypesApi::INTERFACE_BIND_NAME, TaxRuleProductTypes::class);
        $this->app->bind(CarrierCustomerTypesApi::INTERFACE_BIND_NAME, CarrierCustomerTypes::class);
        $this->app->bind(TaxRuleCustomerTypesApi::INTERFACE_BIND_NAME, TaxRuleCustomerTypes::class);
    }

    /**
     * @return void
     */
    protected function declareResources()
    {
        $confDir       = $this->rootDir.'config'.DIRECTORY_SEPARATOR;
        $transDir      = $this->rootDir.'resources'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR;
        $transFileName = Translate::FILE_NAME_WO_EXT.'.php';

        $this->publishes([

            $confDir.'config.php' => config_path(CoreApiConfig::CONFIG_FILE_NAME_WO_EXT.'.php', 'config'),
            $transDir.'en'.DIRECTORY_SEPARATOR.$transFileName  => $this->getLangPath('en', $transFileName),

        ]);

        $this->loadTranslationsFrom($transDir, self::PACKAGE_NAMESPACE);
    }

    /**
     * @param string $languageCode
     * @param string $fileName
     *
     * @return string
     */
    private function getLangPath($languageCode, $fileName)
    {
        $langSubDir = 'resources'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'packages'.
            DIRECTORY_SEPARATOR.$languageCode.DIRECTORY_SEPARATOR.self::PACKAGE_NAMESPACE.DIRECTORY_SEPARATOR;
        return base_path($langSubDir).$fileName;
    }

    /**
     * @return void
     */
    private function registerAuth()
    {
        // 1) Read settings
        // 2) Create factory closures
        // 3) Bind interface to the closures

        // 1)

        $settings = CoreApiConfig::get(CoreApiConfig::KEY_AUTH);

        $aclCacheDuration   = 1;
        $aclCacheKey        = 'employeeAcl';
        $tokenCacheDuration = 1;
        $tokenCachePrefix   = 'employeeToken_';

        if (isset($settings[CoreApiConfig::PARAM_AUTH_CACHE_ACL_IN_MINUTES]) === true) {
            $aclCacheDuration = $settings[CoreApiConfig::PARAM_AUTH_CACHE_ACL_IN_MINUTES];
        }

        if (isset($settings[CoreApiConfig::PARAM_AUTH_CACHE_ACL_KEY]) === true) {
            $aclCacheKey = $settings[CoreApiConfig::PARAM_AUTH_CACHE_ACL_KEY];
        }

        if (isset($settings[CoreApiConfig::PARAM_AUTH_CACHE_TOKENS_IN_MINUTES]) === true) {
            $tokenCacheDuration = $settings[CoreApiConfig::PARAM_AUTH_CACHE_TOKENS_IN_MINUTES];
        }

        if (isset($settings[CoreApiConfig::PARAM_AUTH_CACHE_TOKENS_PREFIX]) === true) {
            $tokenCachePrefix = $settings[CoreApiConfig::PARAM_AUTH_CACHE_TOKENS_PREFIX];
        }

        // 2)

        $getAcl = function (ContainerInterface $app) use ($aclCacheKey, $aclCacheDuration) {
            return new CachePermissionManager(
                $app->make(AuthInterface::class),
                $app->make(CacheInterface::class),
                $app,
                $aclCacheKey,
                $aclCacheDuration
            );
        };

        $getTokenManager = function (ContainerInterface $app) use ($tokenCachePrefix, $tokenCacheDuration) {
            return new CacheTokenManager(
                $app->make(CacheInterface::class),
                $tokenCachePrefix,
                $tokenCacheDuration
            );
        };

        // 3)

        $this->app->singleton(PermissionManagerInterface::class, $getAcl);
        $this->app->singleton(TokenManagerInterface::class, $getTokenManager);
    }
}
