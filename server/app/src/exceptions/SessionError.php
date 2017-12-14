<?php
namespace petitphotobox\exceptions;
use petitphotobox\exception\AppError;
use petitphotobox\exceptions\Codes;

class SessionError extends AppError
{
  /**
   * Constructor.
   *
   * @param string    $message  Message
   * @param Exception $previous Previous exception
   */
  public function __construct($message, $previous = null)
  {
      parent::__construct($message, Codes::SESSION_ERROR, $previous);
  }
}
