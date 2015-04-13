<?php namespace Neomerx\CoreApi\Api\Stores;

use \Neomerx\Core\Models\Store;
use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Stores\StoreRepositoryInterface;
use \Neomerx\Core\Repositories\Addresses\AddressRepositoryInterface;

class Stores extends SingleResourceApi implements StoresInterface
{
    const EVENT_PREFIX = 'Api.Store.';

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepo;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * Constructor.
     *
     * @param AddressRepositoryInterface $addressRepo
     * @param StoreRepositoryInterface   $storeRepo
     */
    public function __construct(
        AddressRepositoryInterface $addressRepo,
        StoreRepositoryInterface $storeRepo
    ) {
        $this->addressRepo = $addressRepo;
        $this->storeRepo   = $storeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Store::FIELD_CODE         => SearchGrammar::TYPE_STRING,
            Store::FIELD_NAME         => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            Store::withAddress(),
            Store::withWarehouses(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->storeRepo;
    }

    /**
     * @inheritdoc
     * @return Store
     */
    protected function getInstance(array $input)
    {
        /** @var Address $address */
        $address = $this->keyToModel($input, self::PARAM_ID_ADDRESS, $this->addressRepo);

        return $this->storeRepo->instance($address, $input);
    }

    /**
     * @inheritdoc
     * @return Store
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Address $address */
        $address = $this->keyToModel($input, self::PARAM_ID_ADDRESS, $this->addressRepo);

        /** @var Store $resource */
        $this->storeRepo->fill($resource, $address, $input);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Store $resource */
        return new StoreArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @return Store
     */
    public function getDefault()
    {
        return $this->storeRepo->read(Store::DEFAULT_CODE, $this->getResourceRelations());
    }
}
