<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Auth;
use \Input;
use \Response;
use \Neomerx\CoreApi\Api\Facades\EmployeeLogin;
use \Neomerx\CoreApi\Api\Auth\EmployeeLoginInterface;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use \Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;

/**
 * @package Neomerx\CoreApi
 */
final class EmployeeLoginControllerJson extends BaseController
{
    /** Auth token key */
    const TOKEN_KEY = 'token';

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    final public function login()
    {
        $login    = Input::json('login');
        $password = Input::json('password');

        return $this->tryAndCatchWrapper('loginImpl', [$login, $password]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    final public function logout()
    {
        return $this->tryAndCatchWrapper('logoutImpl', []);
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @return array
     */
    protected function loginImpl($login, $password)
    {
        $reply = EmployeeLogin::login($login, $password);
        $token = null;
        /** @var AuthenticatableInterface $user */
        if ($reply === EmployeeLoginInterface::CODE_OK && ($user = Auth::user()) !== null) {
            $token = (object)[self::TOKEN_KEY => $user->getRememberToken()];
        }
        $reply = ($reply === EmployeeLoginInterface::CODE_OK ?
            SymfonyResponse::HTTP_CREATED : SymfonyResponse::HTTP_UNAUTHORIZED);
        return [$token, $reply];
    }

    /**
     * @return array
     */
    protected function logoutImpl()
    {
        $reply = EmployeeLogin::logout();
        $reply = ($reply === EmployeeLoginInterface::CODE_OK ?
            SymfonyResponse::HTTP_NO_CONTENT : SymfonyResponse::HTTP_UNAUTHORIZED);
        return [null, $reply];
    }

    /**
     * @param array|string $data
     * @param int          $status
     *
     * @return SymfonyResponse
     */
    protected function formatReply($data, $status)
    {
        $response = Response::json($data, $status);
        return $response;
    }
}
