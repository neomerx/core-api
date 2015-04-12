<?php namespace Neomerx\CoreApi\Api\Addresses;

use \Neomerx\Core\Models\Address;
use \Neomerx\CoreApi\Events\EventArgs;

class AddressArgs extends EventArgs
{
    /**
     * @var Address
     */
    private $address;

    /**
     * @param string    $name
     * @param Address   $address
     * @param EventArgs $args
     */
    public function __construct($name, Address $address, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->address = $address;
    }

    /**
     * @return Address
     */
    public function getModel()
    {
        return $this->address;
    }
}
