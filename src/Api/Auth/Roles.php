<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Auth\RoleRepositoryInterface;

class Roles extends SingleResourceApi implements RolesInterface
{
    const EVENT_PREFIX = 'Api.Role.';

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepo;

    /**
     * Constructor.
     *
     * @param RoleRepositoryInterface $roleRepo
     */
    public function __construct(RoleRepositoryInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Role::FIELD_CODE          => SearchGrammar::TYPE_STRING,
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
        return $this->roleRepo;
    }

    /**
     * @inheritdoc
     * @return Role
     */
    protected function getInstance(array $input)
    {
        return $this->roleRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return Role
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Role $resource */
        $this->roleRepo->fill($resource, $input);

        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Role $resource */
        return new RoleArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }
}
