<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Image;
use \Illuminate\Support\Facades\Facade;
use \Neomerx\CoreApi\Api\Images\ImagesInterface;

/**
 * @see ImagesInterface
 *
 * @method static Image create(array $input)
 * @method static Image read(string $code)
 * @method static void  update(string $code, array $input)
 * @method static void  delete(string $code)
 */
class Images extends Facade
{
    const INTERFACE_BIND_NAME = ImagesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
