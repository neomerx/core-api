<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Exception;
use \Neomerx\Core\Support as S;
use \Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\Config;
use \Neomerx\CoreApi\Support\Translate as T;
use \Neomerx\Core\Exceptions\ValidationException;
use \Illuminate\Foundation\Bus\DispatchesCommands;
use \Neomerx\Core\Exceptions\InvalidArgumentException;
use \Illuminate\Routing\Controller as IlluminateController;
use \Neomerx\Core\Exceptions\Exception as NeomerxBaseException;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class BaseController extends IlluminateController
{
    use DispatchesCommands;

    /**
     * Wraps class calls with try catch.
     *
     * @param string $methodName
     * @param array  $parameters
     *
     * @return \Illuminate\Http\JsonResponse
     */
    final protected function tryAndCatchWrapper($methodName, array $parameters)
    {
        $errorReply = [];
        $codeOnError = SymfonyResponse::HTTP_BAD_REQUEST;
        try {
            list($data, $code) = call_user_func_array([$this, $methodName], $parameters);
            $code = ($code ? $code : SymfonyResponse::HTTP_OK);
            return $this->formatReply($data, $code);
        } catch (InvalidArgumentException $e) {
            $errorReply['type'] = 'invalid_request_error';
            $errorReply['message'] = $e->getMessage();
            $errorReply['param'] = $e->getName();
        } catch (ValidationException $e) {
            $errorReply['type'] = 'validation_error';
            $errorReply['message'] = $e->getMessage();
            $errorReply['validation'] = $e->getValidator()->getMessageBag()->toArray();
        } catch (NeomerxBaseException $e) {
            $errorReply['type'] = 'api_error';
            $errorReply['message'] = $e->getMessage();

            /** @noinspection PhpUndefinedMethodInspection */
            Log::error('Api error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
        } catch (Exception $e) {
            $message = $e->getMessage();

            /** @noinspection PhpUndefinedMethodInspection */
            Log::critical($message);

            // Hide error message in production
            /** @noinspection PhpUndefinedMethodInspection */
            if (!Config::get('app.debug')) {
                $message = T::trans(T::KEY_ERR_UNEXPECTED);
            }

            $errorReply['type'] = 'api_error';
            $errorReply['message'] = $message;
        }

        return $this->formatReply($errorReply, $codeOnError);
    }

    /**
     * @param string|array $data
     * @param int          $status
     *
     * @return mixed
     */
    abstract protected function formatReply($data, $status);
}
