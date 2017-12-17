<?php
namespace petitphotobox\controller;
use \Exception;
use soloproyectos\http\controller\HttpController;
use petitphotobox\exception\AppError;
use petitphotobox\exception\ClientException;

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
   * {@inheritdoc}
   *
   * @return void
   */
  public function apply()
  {
    try {
      parent::apply();
    } catch (ClientException $e) {
      header("HTTP/1.0 400 Client Error");

      // TODO: setStatus($e->getCode(), $e->getMessage());
      $this->response->setStatusCode($e->getCode());
      $this->response->setStatusMessage($e->getMessage());
    } catch (AppError $e) {
      header("HTTP/1.0 500 Application Error");

      $this->response->setStatusCode($e->getCode());
      $this->response->setStatusMessage($e->getMessage());

      echo $this->response;
      throw $e;
    } catch (Exception $e) {
      header("HTTP/1.0 500 Internal Server Error");

      $this->response->setStatusCode(500);
      $this->response->setStatusMessage($e->getMessage());

      echo $this->response;
      throw $e;
    }

    $this->printResponse();
  }

  /**
   * Prints the response.
   *
   * Eventually it sets a client error status in the HTTP header.
   *
   * @return void
   */
  // TODO: rename by printDocument()
  public function printResponse()
  {
    echo $this->response;
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
  // TODO: remove this function (is confusing)
  public function __toString()
  {
    return json_encode($this->_data);
  }
}
