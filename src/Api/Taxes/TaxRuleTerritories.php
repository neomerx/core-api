<?php namespace Neomerx\CoreApi\Api\Taxes;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\TaxRule;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\TaxRuleTerritory;
use \Neomerx\CoreApi\Api\Common\TerritoryParser;
use \Neomerx\CoreApi\Api\DependentSingleResourceApi;
use \Neomerx\Core\Repositories\Taxes\TaxRuleRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\RegionRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\CountryRepositoryInterface;
use \Neomerx\Core\Repositories\Taxes\TaxRuleTerritoryRepositoryInterface;

class TaxRuleTerritories extends DependentSingleResourceApi implements TaxRuleTerritoriesInterface
{
    const EVENT_PREFIX = 'Api.TaxRuleTerritory.';

    /**
     * @var TaxRuleRepositoryInterface
     */
    private $ruleRepo;

    /**
     * @var TaxRuleTerritoryRepositoryInterface
     */
    private $territoryRepo;

    /**
     * @param TaxRuleTerritoryRepositoryInterface $territoryRepo
     * @param TaxRuleRepositoryInterface          $ruleRepo
     * @param RegionRepositoryInterface           $regionRepo
     * @param CountryRepositoryInterface          $countryRepo
     */
    public function __construct(
        TaxRuleTerritoryRepositoryInterface $territoryRepo,
        TaxRuleRepositoryInterface $ruleRepo,
        RegionRepositoryInterface $regionRepo,
        CountryRepositoryInterface $countryRepo
    ) {
        parent::__construct($ruleRepo, self::PARAM_ID_RULE, self::PARAM_ID);

        $this->ruleRepo        = $ruleRepo;
        $this->territoryRepo   = $territoryRepo;
        $this->territoryParser = new TerritoryParser($regionRepo, $countryRepo);
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->territoryRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            TaxRuleTerritory::withTerritory(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            TaxRuleTerritory::FIELD_ID             => SearchGrammar::TYPE_INT,
            TaxRuleTerritory::FIELD_ID_TAX_RULE    => SearchGrammar::TYPE_INT,
            TaxRuleTerritory::FIELD_TERRITORY_TYPE => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP              => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE              => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var TaxRuleTerritory $resource */
        return new TaxRuleTerritoryArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function instanceWithParent(BaseModel $parentResource, array $input)
    {
        /** @var TaxRule $parentResource */

        list($parsedDataType, $territoryCode, $territoryRepo) = $this->territoryParser->parseTerritoryFromArray($input);

        switch($parsedDataType) {
            case TerritoryParser::PARSED_DATA_REGION:
            case TerritoryParser::PARSED_DATA_COUNTRY:
                $territory = $this->readResourceFromRepository($territoryCode, $territoryRepo);
                return $this->territoryRepo->instance($parentResource, $territory);
            case TerritoryParser::PARSED_DATA_ALL_REGIONS:
                return $this->territoryRepo->instanceAllRegions($parentResource);
            default:
                assert('$parsedDataType === '.TerritoryParser::PARSED_DATA_ALL_COUNTRIES);
                return $this->territoryRepo->instanceAllCountries($parentResource);
        }
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var TaxRuleTerritory $resource */

        /** @var TaxRule $rule */
        $rule = $this->keyToModel($input, self::PARAM_ID_RULE, $this->ruleRepo);

        $territoryCode = S\arrayGetValue($input, self::PARAM_TERRITORY_CODE);
        $territoryType = S\arrayGetValue($input, self::PARAM_TERRITORY_TYPE);

        if ($territoryType === null) {
            // territory not changing
            $this->territoryRepo->fill($resource, $rule);
            return $resource;
        }

        list($parsedDataType, $territoryCode, $territoryRepo) =
            $this->territoryParser->parseTerritory($territoryCode, $territoryType);

        switch ($parsedDataType) {
            case TerritoryParser::PARSED_DATA_REGION:
            case TerritoryParser::PARSED_DATA_COUNTRY:
                $territory = $this->readResourceFromRepository($territoryCode, $territoryRepo);
                $this->territoryRepo->fill($resource, $rule, $territory);
                break;
            case TerritoryParser::PARSED_DATA_ALL_REGIONS:
                $this->territoryRepo->fillAllRegions($resource, $rule);
                break;
            default:
                assert('$parsedDataType === '.TerritoryParser::PARSED_DATA_ALL_COUNTRIES);
                $this->territoryRepo->fillAllCountries($resource, $rule);
                break;
        }

        return $resource;
    }

    /**
     * @inheritdoc
     */
    public function createWithRule(TaxRule $rule, array $input)
    {
        return $this->createWith($rule, $input);
    }

    /**
     * @inheritdoc
     */
    public function readWithRule($ruleId, $resourceId)
    {
        settype($ruleId, 'int');
        settype($resourceId, 'int');
        return $this->readWith($ruleId, $resourceId);
    }

    /**
     * @inheritdoc
     */
    public function deleteWithRule($ruleId, $resourceId)
    {
        settype($ruleId, 'int');
        settype($resourceId, 'int');
        $this->deleteWith($ruleId, $resourceId);
    }
}
