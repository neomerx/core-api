<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\CoreApi\Api\Auth\EmployeeLoginInterface;

/**
 * @see EmployeeLoginInterface
 *
 * @method static int login(string $login, string $password)
 * @method static int logout()
 */
class EmployeeLogin extends Facade
{
    const INTERFACE_BIND_NAME = EmployeeLoginInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
