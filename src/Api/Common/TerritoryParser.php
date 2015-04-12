<?php namespace Neomerx\CoreApi\Api\Common;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Territories\RegionRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\CountryRepositoryInterface;

class TerritoryParser
{
    const PARAM_CODE   = 'territory_code';
    const PARAM_TYPE   = 'territory_type';
    const TYPE_COUNTRY = 'country';
    const TYPE_REGION  = 'region';

    const PARSED_DATA_REGION        = 0;
    const PARSED_DATA_COUNTRY       = 1;
    const PARSED_DATA_ALL_REGIONS   = 2;
    const PARSED_DATA_ALL_COUNTRIES = 3;

    /**
     * @var RegionRepositoryInterface
     */
    private $regionRepo;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepo;

    /**
     * @param RegionRepositoryInterface  $regionRepo
     * @param CountryRepositoryInterface $countryRepo
     */
    public function __construct(RegionRepositoryInterface $regionRepo, CountryRepositoryInterface $countryRepo)
    {
        $this->regionRepo  = $regionRepo;
        $this->countryRepo = $countryRepo;
    }

    /**
     * @param string $code
     * @param string $type
     *
     * @return array [int $parsedDataType, string $territoryCode, RepositoryInterface $territoryRepo]
     *
     * @see RepositoryInterface
     */
    public function parseTerritory($code, $type)
    {
        $codeSpecified = isset($code);
        $isRegion      = S\arrayGetValueEx([
            self::TYPE_REGION  => true,
            self::TYPE_COUNTRY => false,
        ], $type);

        if ($codeSpecified === false) {
            return [
                $isRegion === true ? self::PARSED_DATA_ALL_REGIONS : self::PARSED_DATA_ALL_COUNTRIES,
                null,
                null
            ];
        } else {
            return [
                $isRegion === true ? self::PARSED_DATA_REGION : self::PARSED_DATA_COUNTRY,
                $code,
                $isRegion === true ? $this->regionRepo : $this->countryRepo
            ];
        }
    }

    /**
     * @param array $input
     *
     * @return array
     */
    public function parseTerritoryFromArray(array $input)
    {
        return $this->parseTerritory(
            S\arrayGetValue($input, self::PARAM_CODE),
            S\arrayGetValueEx($input, self::PARAM_TYPE)
        );
    }
}
