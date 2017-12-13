<?php
namespace petitphotobox\exceptions;
use petitphotobox\exception\ClientException;

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
      parent::__construct($message, 401, $previous);
  }
}
