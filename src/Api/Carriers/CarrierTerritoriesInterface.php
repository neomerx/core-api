<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CarrierTerritory;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Common\TerritoryParser;

interface CarrierTerritoriesInterface extends CrudInterface
{
    const PARAM_CARRIER_CODE     = 'carrier_code';
    const PARAM_TERRITORY_CODE   = TerritoryParser::PARAM_CODE;
    const PARAM_TERRITORY_TYPE   = TerritoryParser::PARAM_TYPE;
    const TERRITORY_TYPE_COUNTRY = TerritoryParser::TYPE_COUNTRY;
    const TERRITORY_TYPE_REGION  = TerritoryParser::TYPE_REGION;

    /**
     * Create carrier territory.
     *
     * @param array $input
     *
     * @return CarrierTerritory
     */
    public function create(array $input);

    /**
     * Read carrier territory by identifier.
     *
     * @param int $idx
     *
     * @return CarrierTerritory
     */
    public function read($idx);

    /**
     * Search carrier territories.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
