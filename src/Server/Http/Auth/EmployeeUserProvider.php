<?php namespace Neomerx\CoreApi\Server\Http\Auth;

use \stdClass;
use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Auth\Token\User;
use \Neomerx\Core\Models\Employee;
use \Illuminate\Database\Eloquent\Model;
use \Neomerx\Core\Auth\Token\UserProvider;
use \Neomerx\Core\Auth\Token\TokenManagerInterface;
use \Illuminate\Contracts\Hashing\Hasher as HashInterface;
use \Neomerx\Core\Auth\Token\RolePermissionManagerInterface;

class EmployeeUserProvider extends UserProvider
{
    const PROVIDER_NAME = 'employee';

    /**
     * @param HashInterface         $hash
     * @param TokenManagerInterface $tokenManager
     */
    public function __construct(HashInterface $hash, TokenManagerInterface $tokenManager)
    {
        parent::__construct($hash, $tokenManager, Employee::class);
    }

    /**
     * @return array
     */
    protected function getModelRelations()
    {
        return [
            Employee::withRoles(),
        ];
    }

    /**
     * @param Model $userModel
     *
     * @return User
     */
    protected function modelToUser(Model $userModel)
    {
        /** @var Employee $userModel */

        $user = parent::modelToUser($userModel);

        $roles = new stdClass();
        foreach ($userModel->roles as $role) {
            $roles->{$role->{Role::FIELD_ID}} = $role->{Role::FIELD_CODE};
        }
        $user->{RolePermissionManagerInterface::USER_AUTH_ROLES} = $roles;

        return $user;
    }
}
