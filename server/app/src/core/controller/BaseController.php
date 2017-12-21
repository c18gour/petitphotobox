<?php
namespace petitphotobox\core\controller;
use \Exception;
use petitphotobox\core\exception\AppException;
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
    } catch (AppException $e) {
      $this->_setStatus($e->getCode(), $e->getMessage());
    } catch (Exception $e) {
      $this->_setStatus(500, $e->getMessage());
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
