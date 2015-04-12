<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Models\CarrierTerritory;
use \Neomerx\CoreApi\Api\Common\TerritoryParser;
use \Neomerx\Core\Repositories\Carriers\CarrierRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\RegionRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\CountryRepositoryInterface;
use \Neomerx\Core\Repositories\Carriers\CarrierTerritoryRepositoryInterface;

class CarrierTerritories extends SingleResourceApi implements CarrierTerritoriesInterface
{
    const EVENT_PREFIX = 'Api.CarrierTerritory.';

    /**
     * @var CarrierRepositoryInterface
     */
    private $carrierRepo;

    /**
     * @var CarrierTerritoryRepositoryInterface
     */
    private $resourceRepo;

    /**
     * @var TerritoryParser
     */
    private $territoryParser;

    /**
     * @param CarrierTerritoryRepositoryInterface $resourceRepo
     * @param CarrierRepositoryInterface          $carrierRepo
     * @param RegionRepositoryInterface           $regionRepo
     * @param CountryRepositoryInterface          $countryRepo
     */
    public function __construct(
        CarrierTerritoryRepositoryInterface $resourceRepo,
        CarrierRepositoryInterface $carrierRepo,
        RegionRepositoryInterface $regionRepo,
        CountryRepositoryInterface $countryRepo
    ) {
        $this->resourceRepo    = $resourceRepo;
        $this->carrierRepo     = $carrierRepo;
        $this->territoryParser = new TerritoryParser($regionRepo, $countryRepo);
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->resourceRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            CarrierTerritory::withTerritory(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            CarrierTerritory::FIELD_TERRITORY_TYPE => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP              => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE              => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var CarrierTerritory $resource */
        return new CarrierTerritoryArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Carrier $carrier */
        $carrier = $this->keyToModelEx($input, self::PARAM_CARRIER_CODE, $this->carrierRepo);

        list($parsedDataType, $territoryCode, $territoryRepo) = $this->territoryParser->parseTerritoryFromArray($input);

        switch($parsedDataType) {
            case TerritoryParser::PARSED_DATA_REGION:
            case TerritoryParser::PARSED_DATA_COUNTRY:
                $territory = $this->readResourceFromRepository($territoryCode, $territoryRepo);
                return $this->resourceRepo->instance($carrier, $territory);
            case TerritoryParser::PARSED_DATA_ALL_REGIONS:
                return $this->resourceRepo->instanceAllRegions($carrier);
            default:
                assert('$parsedDataType === '.TerritoryParser::PARSED_DATA_ALL_COUNTRIES);
                return $this->resourceRepo->instanceAllCountries($carrier);
        }
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var CarrierTerritory $resource */

        /** @var Carrier $carrier */
        $carrier = $this->keyToModel($input, self::PARAM_CARRIER_CODE, $this->carrierRepo);

        $territoryCode = S\arrayGetValue($input, self::PARAM_TERRITORY_CODE);
        $territoryType = S\arrayGetValue($input, self::PARAM_TERRITORY_TYPE);

        if ($territoryType === null) {
            // territory not changing
            $this->resourceRepo->fill($resource, $carrier);
            return $resource;
        }

        list($parsedDataType, $territoryCode, $territoryRepo) =
            $this->territoryParser->parseTerritory($territoryCode, $territoryType);

        switch ($parsedDataType) {
            case TerritoryParser::PARSED_DATA_REGION:
            case TerritoryParser::PARSED_DATA_COUNTRY:
                $territory = $this->readResourceFromRepository($territoryCode, $territoryRepo);
                $this->resourceRepo->fill($resource, $carrier, $territory);
                break;
            case TerritoryParser::PARSED_DATA_ALL_REGIONS:
                $this->resourceRepo->fillAllRegions($resource, $carrier);
                break;
            default:
                assert('$parsedDataType === '.TerritoryParser::PARSED_DATA_ALL_COUNTRIES);
                $this->resourceRepo->fillAllCountries($resource, $carrier);
                break;
        }

        return $resource;
    }
}
