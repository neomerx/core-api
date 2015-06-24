<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ObjectType;
use \Neomerx\Core\Models\RoleObjectType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Auth\RoleRepositoryInterface;
use \Neomerx\Core\Repositories\Auth\ObjectTypeRepositoryInterface;
use \Neomerx\Core\Repositories\Auth\RoleObjectTypeRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 */
class RoleObjectTypes extends SingleResourceApi implements RoleObjectTypesInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.RoleObjectType.';

    /**
     * @var RoleObjectTypeRepositoryInterface
     */
    private $roleObjectTypeRepo;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepo;

    /**
     * @var ObjectTypeRepositoryInterface
     */
    private $objectTypeRepo;

    /**
     * @param RoleObjectTypeRepositoryInterface $roleObjectTypeRepo
     * @param RoleRepositoryInterface           $roleRepo
     * @param ObjectTypeRepositoryInterface     $objectTypeRepo
     */
    public function __construct(
        RoleObjectTypeRepositoryInterface $roleObjectTypeRepo,
        RoleRepositoryInterface $roleRepo,
        ObjectTypeRepositoryInterface $objectTypeRepo
    ) {
        $this->roleRepo           = $roleRepo;
        $this->objectTypeRepo     = $objectTypeRepo;
        $this->roleObjectTypeRepo = $roleObjectTypeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Role $role */
        $role = $this->keyToModelEx($input, self::PARAM_ROLE_CODE, $this->roleRepo);
        /** @var ObjectType $objectType */
        $objectType = $this->keyToModelEx($input, self::PARAM_ID_TYPE, $this->objectTypeRepo);
        return $this->roleObjectTypeRepo->instance($role, $objectType, $input);
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->roleObjectTypeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            RoleObjectType::withRole(),
            RoleObjectType::withType(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            RoleObjectType::FIELD_ID      => SearchGrammar::TYPE_INT,
            RoleObjectType::FIELD_ID_ROLE => SearchGrammar::TYPE_INT,
            RoleObjectType::FIELD_ID_TYPE => SearchGrammar::TYPE_INT,
            SearchGrammar::LIMIT_SKIP     => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE     => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var RoleObjectType $resource */
        return new RoleObjectTypeArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var RoleObjectType $resource */

        /** @var Role $role */
        $role = $this->keyToModel($input, self::PARAM_ROLE_CODE, $this->roleRepo);

        /** @var ObjectType $objectType */
        $objectType = $this->keyToModel($input, self::PARAM_ID_TYPE, $this->objectTypeRepo);

        $this->roleObjectTypeRepo->fill($resource, $role, $objectType, $input);
        return $resource;
    }
}
