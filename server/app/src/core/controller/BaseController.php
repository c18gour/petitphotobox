<?php
namespace petitphotobox\core\controller;
use \Exception;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\BaseDocument;
use soloproyectos\http\controller\HttpController;

abstract class BaseController extends HttpController
{
  /**
   * Gets the current document.
   *
   * @return BaseDocument
   */
  abstract public function getDocument();

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function processRequest()
  {
    try {
      parent::processRequest();
    } catch (ClientException $e) {
      header("HTTP/1.0 400 Client Error");
      $this->_setStatus($e->getCode(), $e->getMessage());
    } catch (AppError $e) {
      header("HTTP/1.0 500 Application Error");
      $this->_setStatus($e->getCode(), $e->getMessage());
      throw $e;
    }catch (Exception $e) {
      header("HTTP/1.0 500 Internal Server Error");
      $this->_setStatus(500, $e->getMessage());
      throw $e;
    } finally {
      echo $this->getDocument();
    }
  }

  /**
   * Set code and message status.
   *
   * @param int    $code    Status code
   * @param string $message Status message
   *
   * @return void;
   */
  private function _setStatus($code, $message)
  {
    $doc = $this->getDocument();
    $doc->setStatusCode($code);
    $doc->setStatusMessage($message);
  }
}
