<?php namespace Neomerx\CoreApi\Api\Employees;

use \Neomerx\Core\Models\EmployeeRole;
use \Neomerx\CoreApi\Events\EventArgs;

/**
 * @package Neomerx\CoreApi
 */
class EmployeeRoleArgs extends EventArgs
{
    /**
     * @var EmployeeRole
     */
    private $resource;

    /**
     * @param string       $name
     * @param EmployeeRole $resource
     * @param EventArgs    $args
     */
    public function __construct($name, EmployeeRole $resource, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->resource = $resource;
    }

    /**
     * @return EmployeeRole
     */
    public function getModel()
    {
        return $this->resource;
    }
}
