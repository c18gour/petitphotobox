<?php
namespace petitphotobox\controllers;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\exceptions\SessionError;
use soloproyectos\arr\Arr;
use soloproyectos\http\upload\HttpUpload;
use soloproyectos\text\Text;

class ImageUploadController extends Controller
{
  private $_user;
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

  /**
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onOpenRequest()
  {
    if ($_SERVER["REQUEST_METHOD"] != "OPTIONS") {
      $this->_user = UserAuth::getInstance($this->db);

      if ($this->_user == null) {
        throw new SessionError("expiredSession");
      }
    }
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    if (!Arr::exist($_FILES, "file")) {
        throw new ClientException("requiredFields");
    }

    $upload = new HttpUpload("file");
    if ($upload->getType() != "image/jpeg") {
      throw new ClientException("imageUpload.onlyJpegImagesAreAllowed");
    }

    $account = $this->_user->getAccount();

    try {
      $this->_path = $account->upload(
        $upload->getTempName(), $upload->getName()
      );
    } catch (DropboxClientException $e) {
      throw new SessionError($e->getMessage());
    }
  }
}
