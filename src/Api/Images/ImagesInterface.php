<?php namespace Neomerx\CoreApi\Api\Images;

use \Neomerx\Core\Models\Image;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ImageProperties;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface ImagesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ORIGINAL_FILE_NAME = Image::FIELD_ORIGINAL_FILE;
    /** Parameter key */
    const PARAM_ORIGINAL_FILE_DATA = 'uploaded_data';
    /** Parameter key */
    const PARAM_PATHS              = Image::FIELD_PATHS;
    /** Parameter key */
    const PARAM_PROPERTIES         = Image::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_ALT     = ImageProperties::FIELD_ALT;

    /**
     * Create image format.
     *
     * @param array $input
     *
     * @return Image
     */
    public function create(array $input);

    /**
     * Read image by identifier.
     *
     * @param int $idx
     *
     * @return Image
     */
    public function read($idx);

    /**
     * Search images.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
