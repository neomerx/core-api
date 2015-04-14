<?php namespace Neomerx\CoreApi\Api\Addresses;

use \Neomerx\Core\Models\Address;
use \Neomerx\CoreApi\Api\CrudInterface;

/**
 * @package Neomerx\CoreApi
 */
interface AddressesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ADDRESS1    = Address::FIELD_ADDRESS1;
    /** Parameter key */
    const PARAM_ADDRESS2    = Address::FIELD_ADDRESS2;
    /** Parameter key */
    const PARAM_CITY        = Address::FIELD_CITY;
    /** Parameter key */
    const PARAM_POSTCODE    = Address::FIELD_POSTCODE;
    /** Parameter key */
    const PARAM_REGION_CODE = 'region_code';

    /**
     * Create address.
     *
     * @param array $input
     *
     * @return Address
     */
    public function create(array $input);

    /**
     * Read address by identifier.
     *
     * @param int $addressId
     *
     * @return Address
     */
    public function read($addressId);

    /**
     * Update address.
     *
     * @param int   $addressId
     * @param array $input
     *
     * @return void
     */
    public function update($addressId, array $input);

    /**
     * Delete address.
     *
     * @param int $addressId
     *
     * @return void
     */
    public function delete($addressId);
}
