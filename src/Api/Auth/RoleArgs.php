<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\Role;
use \Neomerx\CoreApi\Events\EventArgs;

/**
 * @package Neomerx\CoreApi
 */
class RoleArgs extends EventArgs
{
    /**
     * @var Role
     */
    private $role;

    /**
     * @param string    $name
     * @param Role      $role
     * @param EventArgs $args
     */
    public function __construct($name, Role $role, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->role = $role;
    }

    /**
     * @return Role
     */
    public function getModel()
    {
        return $this->role;
    }
}
