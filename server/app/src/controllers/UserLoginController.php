<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\SystemAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\model\Document;

class UserLoginController extends Controller
{
  /**
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    return new Document(
      [
        "url" => SystemAuth::getUrl()
      ]
    );
  }
}
