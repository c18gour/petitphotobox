<?php
namespace petitphotobox\core\model\document;

abstract class Document
{
  private $_statusCode = 0;
  private $_statusMessage = "";

  /**
   * Gets status code.
   *
   * @return int
   */
  public function getStatusCode()
  {
    return $this->_statusCode;
  }

  /**
   * Sets status code.
   *
   * @param int $code Status code
   *
   * @return void
   */
  public function setStatusCode($code)
  {
    $this->_statusCode = $code;
  }

  /**
   * Gets status message.
   *
   * @return string
   */
  public function getStatusMessage()
  {
    return $this->_statusMessage;
  }

  /**
   * Sets status message.
   *
   * @param string $message Status message
   *
   * @return void
   */
  public function setStatusMessage($message)
  {
    $this->_statusMessage = $message;
  }

  /**
   * Gets a 'plain object' representing the current instance.
   *
   * @return object
   */
  abstract protected function getJsonObject();

  /**
   * Gets a string representation of the current instance.
   *
   * @return string
   */
  public function __toString()
  {
    return json_encode(
      (object) [
        "status" => [
          "code" => $this->_statusCode,
          "message" => $this->_statusMessage
        ],
        "body" => (object) $this->getJsonObject()
      ]
    );
  }
}
