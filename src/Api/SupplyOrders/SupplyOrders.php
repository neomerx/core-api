<?php namespace Neomerx\CoreApi\Api\SupplyOrders;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Currency;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\Supplier;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Warehouse;
use \Neomerx\Core\Models\SupplyOrder;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Suppliers\SupplierRepositoryInterface;
use \Neomerx\Core\Repositories\Currencies\CurrencyRepositoryInterface;
use \Neomerx\Core\Repositories\Warehouses\WarehouseRepositoryInterface;
use \Neomerx\Core\Repositories\Suppliers\SupplyOrderRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SupplyOrders extends SingleResourceApi implements SupplyOrdersInterface
{
    const EVENT_PREFIX = 'Api.SupplyOrder.';
    const BIND_NAME    = __CLASS__;

    /**
     * @var SupplyOrderRepositoryInterface
     */
    private $supplyOrderRepo;

    /**
     * @var SupplierRepositoryInterface
     */
    private $supplierRepo;

    /**
     * @var WarehouseRepositoryInterface
     */
    private $warehouseRepo;

    /**
     * @var CurrencyRepositoryInterface
     */
    private $currencyRepo;

    /**
     * @var LanguageRepositoryInterface
     */
    private $languageRepo;

    /**
     * @param SupplyOrderRepositoryInterface $supplyOrderRepo
     * @param SupplierRepositoryInterface    $supplierRepo
     * @param WarehouseRepositoryInterface   $warehouseRepo
     * @param CurrencyRepositoryInterface    $currencyRepo
     * @param LanguageRepositoryInterface    $languageRepo
     */
    public function __construct(
        SupplyOrderRepositoryInterface $supplyOrderRepo,
        SupplierRepositoryInterface $supplierRepo,
        WarehouseRepositoryInterface $warehouseRepo,
        CurrencyRepositoryInterface $currencyRepo,
        LanguageRepositoryInterface $languageRepo
    ) {
        $this->supplyOrderRepo = $supplyOrderRepo;
        $this->supplierRepo    = $supplierRepo;
        $this->warehouseRepo   = $warehouseRepo;
        $this->currencyRepo    = $currencyRepo;
        $this->languageRepo    = $languageRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->supplyOrderRepo;
    }

    /**
     * Get model relations to load on read and search.
     *
     * @return array
     */
    protected function getResourceRelations()
    {
        return [
            SupplyOrder::withSupplier(),
            SupplyOrder::withCurrency(),
            SupplyOrder::withLanguage(),
            SupplyOrder::withWarehouse(),
        ];
    }

    /**
     * Get resource search rules.
     *
     * @return array
     */
    protected function getSearchRules()
    {
        return [
            SupplyOrder::FIELD_STATUS => SearchGrammar::TYPE_STRING,
            'expected'                => [SearchGrammar::TYPE_DATE, SupplyOrder::FIELD_EXPECTED_AT],
            'created'                 => [SearchGrammar::TYPE_DATE, SupplyOrder::FIELD_CREATED_AT],
            'updated'                 => [SearchGrammar::TYPE_DATE, SupplyOrder::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var SupplyOrder $resource */
        return new SupplyOrderArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Supplier $supplier */
        $supplier = $this->keyToModelEx($input, self::PARAM_SUPPLIER_CODE, $this->supplierRepo);

        /** @var Warehouse $warehouse */
        $warehouse = $this->keyToModelEx($input, self::PARAM_WAREHOUSE_CODE, $this->warehouseRepo);

        /** @var Currency $currency */
        $currency = $this->keyToModelEx($input, self::PARAM_CURRENCY_CODE, $this->currencyRepo);

        /** @var Language $language */
        $language = $this->keyToModelEx($input, self::PARAM_LANGUAGE_CODE, $this->languageRepo);

        return $this->supplyOrderRepo->instance($supplier, $warehouse, $currency, $language, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var SupplyOrder $resource */

        /** @var Supplier $supplier */
        $supplier = $this->keyToModel($input, self::PARAM_SUPPLIER_CODE, $this->supplierRepo);

        /** @var Warehouse $warehouse */
        $warehouse = $this->keyToModel($input, self::PARAM_WAREHOUSE_CODE, $this->warehouseRepo);

        /** @var Currency $currency */
        $currency = $this->keyToModel($input, self::PARAM_CURRENCY_CODE, $this->currencyRepo);

        /** @var Language $language */
        $language = $this->keyToModel($input, self::PARAM_LANGUAGE_CODE, $this->languageRepo);

        $this->supplyOrderRepo->fill($resource, $supplier, $warehouse, $currency, $language, $input);

        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function onCreating(BaseModel $resource, array $attributes)
    {
        /** @var SupplyOrder $resource */

        parent::onCreating($resource, $attributes);

        $detailsInput = S\arrayGetValue($attributes, self::PARAM_DETAILS);
        if (empty($detailsInput) === true) {
            return;
        }

        /** @var SupplyOrderDetailsInterface $detailsApi */
        $detailsApi = app(SupplyOrderDetailsInterface::class);
        foreach ($detailsInput as $detailsRow) {
            $detailsApi->createWithOrder($resource, $detailsRow);
        }
    }
}
