<?php namespace Neomerx\CoreApi\Api\Auth;

/**
 * @package Neomerx\CoreApi
 */
interface EmployeeLoginInterface
{
    /** Operation successful */
    const CODE_OK                = 0;
    /** Error occurred */
    const CODE_ERROR             = 1;
    /** Too many attempts */
    const CODE_TOO_MANY_ATTEMPTS = 2;

    /**
     * @param string $login
     * @param string $password
     *
     * @return int
     */
    public function login($login, $password);

    /**
     * @return int
     */
    public function logout();
}
