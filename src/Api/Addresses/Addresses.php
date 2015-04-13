<?php namespace Neomerx\CoreApi\Api\Addresses;

use \Neomerx\Core\Models\Region;
use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Addresses\AddressRepositoryInterface;
use \Neomerx\Core\Repositories\Territories\RegionRepositoryInterface;

class Addresses extends SingleResourceApi implements AddressesInterface
{
    const EVENT_PREFIX = 'Api.Address.';

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * @var RegionRepositoryInterface
     */
    private $regionRepo;

    /**
     * @param AddressRepositoryInterface $addressRepo
     * @param RegionRepositoryInterface  $regionRepo
     */
    public function __construct(AddressRepositoryInterface $addressRepo, RegionRepositoryInterface $regionRepo)
    {
        $this->addressRepo = $addressRepo;
        $this->regionRepo  = $regionRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [Address::withRegion()];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->addressRepo;
    }

    /**
     * @inheritdoc
     * @return Address
     */
    protected function getInstance(array $input)
    {
        /** @var Region $region */
        $region = $this->keyToModel($input, self::PARAM_REGION_CODE, $this->regionRepo);
        return $this->addressRepo->instance($input, $region);
    }

    /**
     * @inheritdoc
     * @return Address
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Address $resource */
        /** @var Region $region */
        $region = $this->keyToModel($input, self::PARAM_REGION_CODE, $this->regionRepo);
        $this->addressRepo->fill($resource, $input, $region);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Address $resource */
        return new AddressArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }
}
