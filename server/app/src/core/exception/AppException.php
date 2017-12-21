<?php
namespace petitphotobox\core\exception;
use \Exception;

abstract class AppException extends Exception
{
  /**
   * Constructor.
   *
   * @param string $message Message
   * @param int    $code    Exception code (not required)
   */
  public function __construct($message, $code = 0)
  {
    parent::__construct($message, $code);
  }
}
