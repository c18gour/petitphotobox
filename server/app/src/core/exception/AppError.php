<?php
namespace petitphotobox\core\exception;
use petitphotobox\core\exception\AppException;

/**
 * An `AppError` represents an exception caused by the application.
 *
 * For example, if the application can't connect to the database engine it
 * throws an AppError.
 */
class AppError extends AppException
{
  /**
   * Constructor.
   *
   * @param string $message Message
   * @param int    $code    Exception code (not required)
   */
  public function __construct($message, $code = APP_ERROR_CODE)
  {
    parent::__construct($message, $code);
  }
}
