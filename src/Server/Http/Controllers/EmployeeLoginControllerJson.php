<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Auth;
use \Input;
use \Response;
use \Neomerx\CoreApi\Api\Facades\EmployeeLogin;
use \Neomerx\CoreApi\Api\Auth\EmployeeLoginInterface;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class EmployeeLoginControllerJson extends BaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    final public function login()
    {
        $login    = Input::get('login');
        $password = Input::get('password');

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
        if ($reply === EmployeeLoginInterface::CODE_OK && ($user = Auth::user()) !== null) {
            $token = (object)['token' => $user->getRememberToken()];
        }
        return [$token, $this->convertReplyToHttpCode($reply)];
    }

    /**
     * @return array
     */
    protected function logoutImpl()
    {
        $reply = EmployeeLogin::logout();
        return [null, $this->convertReplyToHttpCode($reply)];
    }

    /**
     * @param int $reply
     *
     * @return int
     */
    private function convertReplyToHttpCode($reply)
    {
        switch ($reply)
        {
            case EmployeeLoginInterface::CODE_OK:
                $code = SymfonyResponse::HTTP_OK;
                break;
            case EmployeeLoginInterface::CODE_ERROR:
                $code = SymfonyResponse::HTTP_UNAUTHORIZED;
                break;
            case EmployeeLoginInterface::CODE_TOO_MANY_ATTEMPTS:
                $code = SymfonyResponse::HTTP_TOO_MANY_REQUESTS;
                break;
            default:
                $code = SymfonyResponse::HTTP_BAD_REQUEST;
                break;
        }
        return $code;
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
