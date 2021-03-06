<?php
namespace petitphotobox\exceptions;
use petitphotobox\core\exception\AppError;

class SessionError extends AppError
{
  /**
   * Constructor.
   *
   * @param string $message Message
   */
  public function __construct($message)
  {
    parent::__construct($message, SESSION_ERROR_CODE);
  }
}
