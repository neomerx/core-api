<?php namespace Neomerx\CoreApi\Api\Images;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ImageFormat;
use \Illuminate\Database\Eloquent\Collection;

interface ImageFormatsInterface extends CrudInterface
{
    const PARAM_CODE   = ImageFormat::FIELD_CODE;
    const PARAM_WIDTH  = ImageFormat::FIELD_WIDTH;
    const PARAM_HEIGHT = ImageFormat::FIELD_HEIGHT;

    /**
     * Create image format.
     *
     * @param array $input
     *
     * @return ImageFormat
     */
    public function create(array $input);

    /**
     * Read image format by identifier.
     *
     * @param string $code
     *
     * @return ImageFormat
     */
    public function read($code);

    /**
     * Search image formats.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
