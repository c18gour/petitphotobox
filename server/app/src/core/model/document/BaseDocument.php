<?php
namespace petitphotobox\core\model\document;

abstract class BaseDocument
{
  private $_statusCode = 0;
  private $_statusMessage = "";
  private $_body = [];

  public function getStatusCode()
  {
    return $this->_statusCode;
  }

  public function setStatusCode($code)
  {
    $this->_statusCode = $code;
  }

  public function getStatusMessage()
  {
    return $this->_statusMessage;
  }

  public function setStatusMessage($message)
  {
    $this->_statusMessage = $message;
  }

  protected function getProperty($name)
  {
    return $this->_body[$name];
  }

  protected function setProperty($name, $value)
  {
    $this->_body[$name] = $value;
  }

  public function __toString()
  {
    return json_encode(
      [
        "status" => [
          "code" => $this->_statusCode,
          "message" => $this->_statusMessage
        ],
        "body" => $this->_body
      ]
    );
  }
}
