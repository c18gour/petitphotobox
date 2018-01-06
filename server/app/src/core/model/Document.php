<?php
namespace petitphotobox\core\model;

class Document
{
  private $_statusCode = 0;
  private $_statusMessage = "";
  private $_body = [];

  /**
   * Constructor.
   *
   * @param array $body A serializable body (optional).
   */
  public function __construct($body = [])
  {
    $this->_body = $body;
  }

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
        "body" => (object) $this->_body
      ]
    );
  }
}
