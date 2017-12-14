<?php
namespace petitphotobox\exceptions;

use petitphotobox\exception\AppError;

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
      parent::__construct($message, 502, $previous);
  }
}
