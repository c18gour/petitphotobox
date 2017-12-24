<?php
namespace petitphotobox\exceptions;
use petitphotobox\core\exception\AppError;

class DatabaseError extends AppError
{
  /**
   * Constructor.
   *
   * @param string $message Message
   */
  public function __construct($message)
  {
    parent::__construct($message, DATABASE_ERROR_CODE);
  }
}
