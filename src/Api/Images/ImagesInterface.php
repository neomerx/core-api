<?php namespace Neomerx\CoreApi\Api\Images;

use \Neomerx\Core\Models\Image;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ImageProperties;
use \Illuminate\Database\Eloquent\Collection;

interface ImagesInterface extends CrudInterface
{
    const PARAM_ORIGINAL_FILE_NAME = Image::FIELD_ORIGINAL_FILE;
    const PARAM_ORIGINAL_FILE_DATA = 'uploaded_data';
    const PARAM_PATHS              = Image::FIELD_PATHS;
    const PARAM_PROPERTIES         = Image::FIELD_PROPERTIES;
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
