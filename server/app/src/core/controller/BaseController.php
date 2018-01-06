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

      // prints the document and re-throws the exception
      $doc = $this->getDocument();
      $doc->setStatusCode($e->getCode());
      $doc->setStatusMessage($e->getMessage());
      echo $doc;
      throw $e;
    } catch (AppError $e) {
      header("HTTP/1.0 500 Application Error");

      // prints an empty document and re-throws the exception
      $doc = new Document();
      $doc->setStatusCode($e->getCode());
      $doc->setStatusMessage($e->getMessage());
      echo $doc;
      throw $e;
    }

    echo $this->getDocument();
  }
}
