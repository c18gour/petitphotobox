<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\core\file\File;
use petitphotobox\exceptions\SessionError;
use soloproyectos\arr\Arr;
use soloproyectos\http\exception\HttpException;
use soloproyectos\http\upload\HttpUpload;
use soloproyectos\text\Text;

class ImageUploadController extends Controller
{
  private $_path = "";

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    return new Document([
      "path" => $this->_path
    ]);
  }

  public function onOpenRequest()
  {
    if (
      $_SERVER["REQUEST_METHOD"] != "OPTIONS"
      && !UserAuth::isLogged($this->db)
    ) {
      throw new SessionError("Your session has expired");
    }
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $user = UserAuth::getInstance($this->db);

    if (!Arr::exist($_FILES, "file")) {
        throw new ClientException("File is required");
    }

    $upload = new HttpUpload("file");
    if ($upload->getType() != "image/jpeg") {
      throw new ClientException("Only JPEG images are allowed;");
    }

    // moves the uploaded file
    try {
      $path = $upload->move($user->getDir());
      $this->_path = "images/" . basename($path);
    } catch (HttpException $e) {
      throw new ClientException($e->getMessage());
    }
  }
}
