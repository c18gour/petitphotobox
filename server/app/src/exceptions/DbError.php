<?php
namespace petitphotobox\exceptions;

use petitphotobox\exception\AppError;

class DbError extends AppError
{
  /**
   * Constructor.
   *
   * @param string    $message  Message
   * @param Exception $previous Previous exception
   */
  public function __construct($message, $previous = null)
  {
      parent::__construct($message, 501, $previous);
  }
}
