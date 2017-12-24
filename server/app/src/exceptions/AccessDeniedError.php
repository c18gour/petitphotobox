<?php
namespace petitphotobox\exceptions;
use petitphotobox\core\exception\AppError;

/**
 * This exception happens when the user tries to access to a forbidden page.
 *
 * For example, if the user has already logged and tries to open the 'register
 * controller', it throws an AccessDeniedError.
 */
// TODO: redirect to the 'home' page when the user tries to open the 'register' page.
class AccessDeniedError extends AppError
{
  /**
   * Constructor.
   *
   * @param string $message Message
   */
  public function __construct($message)
  {
    parent::__construct($message, ACCESS_DENIED_ERROR_CODE);
  }
}
