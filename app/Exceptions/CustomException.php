<?php

namespace App\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Exception;

class CustomException extends \Exception implements HttpExceptionInterface
{
    public function getStatusCode()
   {
       return 500;
   }

   /**
    * Returns response headers.
    *
    * @return array Response headers
    */
   public function getHeaders()
   {
       return [];
   }
}
