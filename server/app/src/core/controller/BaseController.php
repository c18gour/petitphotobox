<?php
namespace petitphotobox\core\controller;
use \Exception;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\exceptions\DatabaseError;
use petitphotobox\core\model\Document;
use soloproyectos\db\DbConnector;
use soloproyectos\db\exception\DbException;
use soloproyectos\http\controller\HttpController;

abstract class BaseController extends HttpController
{
  protected $db;

  /**
   * Creates a new instance..
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler(function () {
      try {
        $this->db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);
      } catch (DbException $e) {
        throw new DatabaseError($e->getMessage());
      }
    });
  }

  /**
   * Gets the current document.
   *
   * @return Document
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
      echo $this->getDocument();
      throw $e;
    } catch (AppError $e) {
      header("HTTP/1.0 500 Application Error");
      $this->_printAppError($e->getCode(), $e->getMessage());
      throw $e;
    }

    echo $this->getDocument();
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

  /**
   * Prints a document showing an 'application error'.
   *
   * @param int    $code    Status code
   * @param string $message Status message
   *
   * @return void
   */
  private function _printAppError($code, $message)
  {
    echo json_encode(
      [
        "status" => [
          "code" => $code,
          "message" => $message
        ]
      ]
    );
  }
}
