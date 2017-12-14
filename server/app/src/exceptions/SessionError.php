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
   */
  public function __construct($message)
  {
      parent::__construct($message, Codes::SESSION_ERROR);
  }
}
