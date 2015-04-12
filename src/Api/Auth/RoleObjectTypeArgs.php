<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\RoleObjectType;

class RoleObjectTypeArgs extends EventArgs
{
    /**
     * @var RoleObjectType
     */
    private $resource;

    /**
     * @param string         $name
     * @param RoleObjectType $roleObjectType
     * @param EventArgs      $args
     */
    public function __construct($name, RoleObjectType $roleObjectType, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->resource = $roleObjectType;
    }

    /**
     * @return RoleObjectType
     */
    public function getModel()
    {
        return $this->resource;
    }
}
