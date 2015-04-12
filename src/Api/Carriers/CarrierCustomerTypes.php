<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Models\CarrierCustomerType;
use \Neomerx\Core\Repositories\Carriers\CarrierRepositoryInterface;
use \Neomerx\Core\Repositories\Customers\CustomerTypeRepositoryInterface;
use \Neomerx\Core\Repositories\Carriers\CarrierCustomerTypeRepositoryInterface;

class CarrierCustomerTypes extends SingleResourceApi implements CarrierCustomerTypesInterface
{
    const EVENT_PREFIX = 'Api.CarrierCustomerType.';

    /**
     * @var CarrierRepositoryInterface
     */
    private $carrierRepo;

    /**
     * @var CustomerTypeRepositoryInterface
     */
    private $typeRepo;

    /**
     * @var CarrierCustomerTypeRepositoryInterface
     */
    private $resourceRepo;

    /**
     * @param CarrierCustomerTypeRepositoryInterface $resourceRepo
     * @param CarrierRepositoryInterface             $carrierRepo
     * @param CustomerTypeRepositoryInterface        $typeRepo
     */
    public function __construct(
        CarrierCustomerTypeRepositoryInterface $resourceRepo,
        CarrierRepositoryInterface $carrierRepo,
        CustomerTypeRepositoryInterface $typeRepo
    ) {
        $this->resourceRepo = $resourceRepo;
        $this->carrierRepo  = $carrierRepo;
        $this->typeRepo     = $typeRepo;
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
            CarrierCustomerType::withType(),
            CarrierCustomerType::withCarrier(),
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
        /** @var CarrierCustomerType $resource */
        return new CarrierCustomerTypeArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Carrier $carrier */
        $carrier = $this->keyToModelEx($input, self::PARAM_CARRIER_CODE, $this->carrierRepo);

        /** @var CustomerType $type */
        if (isset($input[self::PARAM_TYPE_CODE]) && $input[self::PARAM_TYPE_CODE] === self::ALL_TYPE_CODES) {
            $type = null;
        } else {
            $type = $this->keyToModelEx($input, self::PARAM_TYPE_CODE, $this->typeRepo);
        }

        return $this->resourceRepo->instance($carrier, $type);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Carrier $carrier */
        $carrier = $this->keyToModel($input, self::PARAM_CARRIER_CODE, $this->carrierRepo);

        /** @var CustomerType $type */
        if (isset($input[self::PARAM_TYPE_CODE]) && $input[self::PARAM_TYPE_CODE] === self::ALL_TYPE_CODES) {
            $type        = null;
            $isTypeEmpty = false;
        } elseif (isset($input[self::PARAM_TYPE_CODE]) && $input[self::PARAM_TYPE_CODE] !== self::ALL_TYPE_CODES) {
            $type        = $this->keyToModelEx($input, self::PARAM_TYPE_CODE, $this->typeRepo);
            $isTypeEmpty = false;
        } else {
            $type        = null;
            $isTypeEmpty = true;
        }

        /** @var CarrierCustomerType $resource */
        // TODO look how this 'isEmpty' problem solved in CarrierTerritories (via repository and more elegantly) --
        $this->resourceRepo->fill($resource, $carrier, $type, $isTypeEmpty); //                                    |
        //                                                         ^-----------------------------------------------
        return $resource;
    }
}
