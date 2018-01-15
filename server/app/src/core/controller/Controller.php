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

class Controller extends HttpController
{
  protected $db;
  private $_document;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->_document = new Document();

    $this->addOpenRequestHandler(function () {
      try {
        $this->db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);
      } catch (DbException $e) {
        throw new DatabaseError($e->getMessage());
      }
    });
  }

  /**
   * {@inheritdoc}
   *
   * @return mixed
   */
  public function getParam($name, $defValue = null)
  {
      return trim(parent::getParam($name, $defValue));
  }

  /**
   * Gets the current document.
   *
   * @return mixed
   */
  public function getDocument()
  {
    return $this->_document;
  }

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

      $doc = $this->getDocument();
      $doc->setStatusCode($e->getCode());
      $doc->setStatusMessage($e->getMessage());
      echo $doc;

      throw $e;
    } catch (AppError $e) {
      header("HTTP/1.0 500 Application Error");

      $doc = new Document();
      $doc->setStatusCode($e->getCode());
      $doc->setStatusMessage($e->getMessage());
      echo $doc;

      throw $e;
    }

    echo $this->getDocument();
  }
}
