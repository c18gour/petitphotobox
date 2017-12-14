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
   */
  public function __construct($message)
  {
      parent::__construct($message, Codes::AUTH_EXCEPTION);
  }
}
