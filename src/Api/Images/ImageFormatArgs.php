<?php namespace Neomerx\CoreApi\Api\Images;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\ImageFormat;

/**
 * @package Neomerx\CoreApi
 */
class ImageFormatArgs extends EventArgs
{
    /**
     * @var ImageFormat
     */
    private $imageFormat;

    /**
     * @param string      $name
     * @param ImageFormat $imageFormat
     * @param EventArgs   $args
     */
    public function __construct($name, ImageFormat $imageFormat, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->imageFormat = $imageFormat;
    }

    /**
     * @return ImageFormat
     */
    public function getModel()
    {
        return $this->imageFormat;
    }
}
