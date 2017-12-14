<?php
namespace petitphotobox\exceptions;
use petitphotobox\exception\AppError;

class SessionError extends AppError
{
  /**
   * Constructor.
   *
   * @param string    $message  Message
   */
  public function __construct($message)
  {
    parent::__construct($message, SESSION_EXCEPTION_CODE);
  }
}
