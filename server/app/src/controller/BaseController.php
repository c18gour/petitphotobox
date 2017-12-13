<?php
namespace petitphotobox\controller;
use petitphotobox\exception\ClientException;
use soloproyectos\http\controller\HttpController;

class BaseController extends HttpController
{
  /**
   * Request response.
   * @var ResponseEntity
   */
  public $response = null;

  /**
   * Constructor.
   */
  public function __construct()
  {
    $this->response = new ResponseEntity();
  }

  /**
   * Sets an status code and message based on an exception.
   *
   * @param Exception $e Exception
   *
   * @return void
   */
  public function setStatusException($e)
  {
    $this->response->setStatusCode($e->getCode());
    $this->response->setStatusMessage($e->getMessage());
  }

  /**
   * Prints the response.
   *
   * Eventually it sets a client error status in the HTTP header.
   *
   * @return void
   */
  public function printResponse()
  {
    if ($this->response->getStatusCode() > 0) {
      header("HTTP/1.0 400 Client Error");
    }

    echo $this->response;
  }

  /**
   * Finalizes the program execution and prints and exception.
   *
   * @param AppException $e Application exception.
   *
   * @return void
   */
  public function finalizeProgramExecution($e)
  {
    header("HTTP/1.0 500 Application Error");
    $this->setStatusException($e);
    echo $this->response;
    die();
  }
}

class ResponseEntity
{
  private $_data = [
    "status" => [
      "code" => 0,
      "message" => ""
    ],
    "body" => []
  ];

  /**
   * Gets the status code.
   *
   * @return int
   */
  public function getStatusCode()
  {
    return $this->_data["status"]["code"];
  }

  /**
   * Sets the status code.
   *
   * @param int $code Status code
   *
   * @return void
   */
  public function setStatusCode($code)
  {
    $this->_data["status"]["code"] = $code;
  }

  /**
   * Gets the status message.
   *
   * @return string
   */
  public function getStatusMessage()
  {
    return $this->_data["status"]["message"];
  }

  /**
   * Sets the status message.
   *
   * @param string $message Status message
   *
   * @return void
   */
  public function setStatusMessage($message)
  {
    $this->_data["status"]["message"] = $message;
  }

  /**
   * Gets a property.
   *
   * @param string $name Property name
   *
   * @return mixed
   */
  public function getProperty($name)
  {
    return $this->_data["body"][$name];
  }

  /**
   * Sets a property value.
   *
   * @param string $name  Property name
   * @param mixed  $value Value
   *
   * @return void
   */
  public function setProperty($name, $value)
  {
    $this->_data["body"][$name] = $value;
  }

  /**
   * Gets the string representation of the current instance.
   *
   * @return string
   */
  public function __toString()
  {
    return json_encode($this->_data);
  }
}
