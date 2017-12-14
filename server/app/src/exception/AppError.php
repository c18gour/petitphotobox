<?php
namespace petitphotobox\exception;
use \Exception;

/**
 * An `AppError` represents an exception caused by the application.
 *
 * For example, if the application can't connect to the database engine it
 * throws an AppError.
 */
class AppError extends Exception
{
  /**
   * Constructor.
   *
   * @param string $message  Message
   * @param int    $code     Exception code
   */
  public function __construct($message = "", $code = 500)
  {
      parent::__construct($message, $code);
  }
}
