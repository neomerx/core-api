<?php namespace Neomerx\CoreApi\Api\Images;

use \Neomerx\Core\Models\Image;
use \Neomerx\CoreApi\Events\EventArgs;

class ImageArgs extends EventArgs
{
    /**
     * @var Image
     */
    private $image;

    /**
     * @param string    $name
     * @param Image     $image
     * @param EventArgs $args
     */
    public function __construct($name, Image $image, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->image = $image;
    }

    /**
     * @return Image
     */
    public function getModel()
    {
        return $this->image;
    }
}
