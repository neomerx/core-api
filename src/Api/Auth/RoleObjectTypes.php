<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ObjectType;
use \Neomerx\Core\Models\RoleObjectType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\DependentSingleResourceApi;
use \Neomerx\Core\Repositories\Auth\RoleRepositoryInterface;
use \Neomerx\Core\Repositories\Auth\ObjectTypeRepositoryInterface;
use \Neomerx\Core\Repositories\Auth\RoleObjectTypeRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 */
class RoleObjectTypes extends DependentSingleResourceApi implements RoleObjectTypesInterface
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
        parent::__construct($roleRepo, self::PARAM_ID_ROLE, self::PARAM_ID);

        $this->roleRepo           = $roleRepo;
        $this->objectTypeRepo     = $objectTypeRepo;
        $this->roleObjectTypeRepo = $roleObjectTypeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        $parentResource = $this->keyToModelEx($input, self::PARAM_ROLE_CODE, $this->roleRepo);
        return $this->instanceWithParent($parentResource, $input);
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
    protected function instanceWithParent(BaseModel $parentResource, array $input)
    {
        assert('$parentResource instanceof '.Role::class);

        /** @var Role $parentResource */

        /** @var ObjectType $objectType */
        $objectType = $this->keyToModelEx($input, self::PARAM_ID_TYPE, $this->objectTypeRepo);

        $resource = $this->roleObjectTypeRepo->instance($parentResource, $objectType, $input);
        return $resource;
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

        $this->roleObjectTypeRepo->fill($resource, $role, $objectType);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    public function createWithRole(Role $role, array $input)
    {
        return $this->createWith($role, $input);
    }

    /**
     * @inheritdoc
     */
    public function readWithRole($roleCode, $roleObjectTypeId)
    {
        /** @var Role $role */
        $role = $this->readResourceFromRepository($roleCode, $this->roleRepo, [], [Role::FIELD_ID]);
        return $this->readWith($role->{Role::FIELD_ID}, $roleObjectTypeId);
    }

    /**
     * @inheritdoc
     */
    public function deleteWithRole($roleCode, $roleObjectTypeId)
    {
        /** @var Role $role */
        $role = $this->readResourceFromRepository($roleCode, $this->roleRepo, [], [Role::FIELD_ID]);
        $this->deleteWith($role->{Role::FIELD_ID}, $roleObjectTypeId);
    }
}
