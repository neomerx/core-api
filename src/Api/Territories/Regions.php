<?php namespace Neomerx\CoreApi\Api\Territories;

use \Neomerx\Core\Models\Region;
use \Neomerx\Core\Models\Country;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Territories\RegionRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\CountryRepositoryInterface;

class Regions extends SingleResourceApi implements RegionsInterface
{
    const EVENT_PREFIX = 'Api.Region.';
    const BIND_NAME    = __CLASS__;

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
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Region::FIELD_CODE        => SearchGrammar::TYPE_STRING,
            Region::FIELD_NAME        => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Region::withCountry()];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->regionRepo;
    }

    /**
     * @inheritdoc
     * @return Region
     */
    protected function getInstance(array $input)
    {
        /** @var Country $country */
        $country = $this->keyToModelEx($input, self::PARAM_COUNTRY_CODE, $this->countryRepo);
        return $this->regionRepo->instance($country, $input);
    }

    /**
     * @inheritdoc
     * @return Region
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Region $resource */
        /** @var Country $country */
        $country = $this->keyToModel($input, self::PARAM_COUNTRY_CODE, $this->countryRepo);
        $this->regionRepo->fill($resource, $country, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Region $resource */
        return new RegionArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }
}
