<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Models\CarrierPostcode;
use \Neomerx\Core\Repositories\Carriers\CarrierRepositoryInterface;
use \Neomerx\Core\Repositories\Carriers\CarrierPostcodeRepositoryInterface;

class CarrierPostcodes extends SingleResourceApi implements CarrierPostcodesInterface
{
    const EVENT_PREFIX = 'Api.CarrierPostcode.';

    /**
     * @var CarrierRepositoryInterface
     */
    private $carrierRepo;

    /**
     * @var CarrierPostcodeRepositoryInterface
     */
    private $resourceRepo;

    /**
     * @param CarrierPostcodeRepositoryInterface $resourceRepo
     * @param CarrierRepositoryInterface         $carrierRepo
     */
    public function __construct(
        CarrierPostcodeRepositoryInterface $resourceRepo,
        CarrierRepositoryInterface $carrierRepo
    ) {
        $this->resourceRepo = $resourceRepo;
        $this->carrierRepo  = $carrierRepo;
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
            CarrierPostcode::withCarrier(),
        ];
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
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var CarrierPostcode $resource */
        return new CarrierPostcodeArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Carrier $carrier */
        $carrier = $this->keyToModelEx($input, self::PARAM_CARRIER_CODE, $this->carrierRepo);

        return $this->resourceRepo->instance($carrier, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Carrier $carrier */
        $carrier = $this->keyToModel($input, self::PARAM_CARRIER_CODE, $this->carrierRepo);

        /** @var CarrierPostcode $resource */
        $this->resourceRepo->fill($resource, $carrier, $input);
        return $resource;
    }
}
