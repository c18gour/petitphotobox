<?php
namespace petitphotobox\exceptions;
use petitphotobox\exception\ClientException;
use petitphotobox\exceptions\Codes;

class AuthException extends ClientException
{
  /**
   * Constructor.
   *
   * @param string    $message  Message
   * @param Exception $previous Previous exception
   */
  public function __construct($message, $previous = null)
  {
      parent::__construct($message, Codes::AUTH_EXCEPTION, $previous);
  }
}
