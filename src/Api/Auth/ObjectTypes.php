<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ObjectType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Auth\ObjectTypeRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 */
class ObjectTypes extends SingleResourceApi implements ObjectTypesInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.ObjectType.';

    /**
     * @var ObjectTypeRepositoryInterface
     */
    private $objectTypeRepo;

    /**
     * Constructor.
     *
     * @param ObjectTypeRepositoryInterface $objectTypeRepo
     */
    public function __construct(ObjectTypeRepositoryInterface $objectTypeRepo)
    {
        $this->objectTypeRepo = $objectTypeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            ObjectType::FIELD_ID      => SearchGrammar::TYPE_INT,
            ObjectType::FIELD_TYPE    => SearchGrammar::TYPE_STRING,
            SearchGrammar::LIMIT_SKIP => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->objectTypeRepo;
    }

    /**
     * @inheritdoc
     *
     * @return ObjectType
     */
    protected function getInstance(array $input)
    {
        return $this->objectTypeRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return ObjectType
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var ObjectType $resource */
        $this->objectTypeRepo->fill($resource, $input);

        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var ObjectType $resource */
        return new ObjectTypeArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }
}
