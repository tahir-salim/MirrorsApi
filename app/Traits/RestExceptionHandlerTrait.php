<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
//use Laravel\Passport\Exceptions\MissingScopeException;
//use League\OAuth2\Server\Exception\OAuthServerException;
use Laravel\Nova\Exceptions\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait RestExceptionHandlerTrait
{

    protected function getJsonResponseForException(Request $request, Exception $e)
    {

        switch (true) {
            case $this->isModelNotFoundException($e):
                return $this->modelNotfound();
            //  case $this->isOAuthServerException($e):
            //                return $this->OAuthError();
            case $this->isAuthenticationException($e):
                return $this->AuthenticationError();
            case $this->isMethodException($e):
                return $this->methodError();
            case $this->isNotFoundHttpException($e):
                return $this->routeError();
            //            case $this->isMissingScopeException($e):
            //                return $this->scopeError();
            case $this->isAuthorizationException($e):
                return $this->authorizationError();
            case $this->isInvalidRequestException($e):
                return $this->invalidRequestError($e->getMessage());
            //            case $this->isRuntimeException($e):
            //                return $this->invalidRuntimeError($e->getMessage());
            default:
                return $this->badRequest($e);
        }
    }

    protected function jsonResponse(array $payload = null, $statusCode = 404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    // Error Responses

    protected function badrequest(Exception $e, $message = "Bad request", $statusCode = 400)
    {
        //      return $this->jsonResponse(['error'=>$e->getTrace()],400);
        return $this->jsonResponse(["message" => $message, "status" => "BAD_REQUEST", "debug" => get_class($e) . " " . $e->getMessage()], $statusCode);
    }

    protected function modelNotFound($message = "Record Not Found", $statusCode = 404)
    {
        return $this->jsonResponse(["message" => $message, "status" => "NOT_FOUND"], $statusCode);
    }

    protected function OAuthError($message = "There was an error authenticating your request", $statusCode = 401)
    {
        return $this->jsonResponse(["message" => $message, "status" => "BAD_AUTH"], $statusCode);
    }

    protected function authorizationError($message = "This action is unauthorized", $statusCode = 403)
    {
        return $this->jsonResponse(["message" => $message, "status" => "BAD_AUTH"], $statusCode);
    }

    protected function AuthenticationError($message = "There was an error authenticating your request", $statusCode = 401)
    {
        return $this->jsonResponse(["message" => $message, "status" => "BAD_AUTH"], $statusCode);
    }

    protected function methodError($message = "The requested method is not allowed for this request", $statusCode = 400)
    {
        return $this->jsonResponse(["message" => $message, "status" => "BAD_METHOD"], $statusCode);
    }

    protected function routeError($message = "The requested route can not be found on the server", $statusCode = 404)
    {
        return $this->jsonResponse(["message" => $message, "status" => "BAD_ROUTE"], $statusCode);
    }

    protected function scopeError($message = "You don't have the right to access this route", $statusCode = 401)
    {
        return $this->jsonResponse(["message" => $message, "status" => "SCOPE_NOT_FOUND"], $statusCode);
    }

    protected function validationError($message = "Validation Error", $statusCode = 400)
    {
        return $this->jsonResponse(["message" => $message, "status" => "VALIDATION_ERROR"], $statusCode);
    }

    protected function invalidCardError($message = "Card is not valid", $statusCode = 400)
    {
        return $this->jsonResponse(["message" => $message, "status" => "INVALID_CARD_ERROR"], $statusCode);
    }

    protected function invalidRequestError($message = "Request is not valid", $statusCode = 400)
    {
        return $this->jsonResponse(["message" => $message, "status" => "INVALID_REQUEST_ERROR"], $statusCode);
    }

    protected function invalidResponseError($message = "Response is not valid", $statusCode = 400)
    {
        return $this->jsonResponse(["message" => $message, "status" => "INVALID_RESPONSE_ERROR"], $statusCode);
    }

    protected function invalidOmnipayError($message = "", $statusCode = 400)
    {
        return $this->jsonResponse(["message" => $message, "status" => "OMNIPAY_ERROR"], $statusCode);
    }

    protected function invalidRuntimeError($message = "", $statusCode = 400)
    {
        return $this->jsonResponse(["message" => $message, "status" => "RUNTIME_ERROR"], $statusCode);
    }

    // Exception Handlers

    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    //    protected function isOAuthServerException(Exception $e)
    //    {
    //        return $e instanceof OAuthServerException;
    //    }

    protected function isAuthenticationException(Exception $e)
    {
        return $e instanceof AuthenticationException;
    }

    protected function isMethodException(Exception $e)
    {
        return $e instanceof MethodNotAllowedHttpException;
    }

    protected function isNotFoundHttpException(Exception $e)
    {
        return $e instanceof NotFoundHttpException;
    }

    //    protected function isMissingScopeException(Exception $e)
    //    {
    //        return $e instanceof MissingScopeException;
    //    }

    protected function isAuthorizationException(Exception $e)
    {
        return $e instanceof AuthorizationException;
    }

    protected function isInvalidRequestException(Exception $e)
    {
        return $e instanceof RequestException;
    }

    //    protected function isRuntimeException(Exception $e)
    //    {
    //        return $e instanceof RuntimeException;
    //    }
}
