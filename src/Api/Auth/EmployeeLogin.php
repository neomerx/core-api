<?php namespace Neomerx\CoreApi\Api\Auth;

use \Auth;
use \Neomerx\Core\Models\Employee;

/**
 * @package Neomerx\CoreApi
 */
class EmployeeLogin implements EmployeeLoginInterface
{
    /**
     * {@inheritdoc}
     */
    public function login($login, $password)
    {
        $success = Auth::attempt([
            Employee::FIELD_EMAIL    => $login,
            Employee::FIELD_PASSWORD => $password,
            Employee::FIELD_ACTIVE   => 1
        ], true, true);
        return $success ? EmployeeLoginInterface::CODE_OK : EmployeeLoginInterface::CODE_ERROR;
    }

    /**
     * {@inheritdoc}
     */
    public function logout()
    {
        Auth::logout();
        return EmployeeLoginInterface::CODE_OK;
    }
}
