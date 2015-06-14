<?php namespace Neomerx\CoreApi\Api\Manufacturers;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\Manufacturer;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Models\ManufacturerProperties;

/**
 * @package Neomerx\CoreApi
 */
interface ManufacturersInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE                   = Manufacturer::FIELD_CODE;
    /** Parameter key */
    const PARAM_ID_ADDRESS             = Manufacturer::FIELD_ID_ADDRESS;
    /** Parameter key */
    const PARAM_PROPERTIES             = Manufacturer::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_NAME        = ManufacturerProperties::FIELD_NAME;
    /** Parameter key */
    const PARAM_PROPERTIES_DESCRIPTION = ManufacturerProperties::FIELD_DESCRIPTION;

    /**
     * Create manufacturer.
     *
     * @param array $input
     *
     * @return Manufacturer
     */
    public function create(array $input);

    /**
     * Read manufacturer by identifier.
     *
     * @param string $code
     *
     * @return Manufacturer
     */
    public function read($code);

    /**
     * Search manufacturers.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
