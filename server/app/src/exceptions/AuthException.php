<?php
namespace petitphotobox\exceptions;
use petitphotobox\core\exception\ClientException;

class AuthException extends ClientException
{
  /**
   * Constructor.
   *
   * @param string $message Message
   */
  public function __construct($message)
  {
    parent::__construct($message, AUTH_EXCEPTION_CODE);
  }
}
