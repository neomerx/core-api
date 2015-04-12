<?php namespace Neomerx\CoreApi\Api\Manufacturers;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\Manufacturer;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Models\ManufacturerProperties;

interface ManufacturersInterface extends CrudInterface
{
    const PARAM_CODE                   = Manufacturer::FIELD_CODE;
    const PARAM_ADDRESS                = Manufacturer::FIELD_ADDRESS;
    const PARAM_PROPERTIES             = Manufacturer::FIELD_PROPERTIES;
    const PARAM_PROPERTIES_NAME        = ManufacturerProperties::FIELD_NAME;
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
