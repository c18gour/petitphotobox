<?php
namespace petitphotobox\core\file;
use petitphotobox\core\exception\AppError;
use soloproyectos\sys\file\SysFile;

class File {
  /**
   * Gets the 'relative path' of an absolute path.
   *
   * @param string $path Absolute path
   */
  static public function getRelativePath($path)
  {
    if (strpos($path, DOCUMENT_ROOT) !== 0) {
        throw new AppError("Not a valid relative path");
    }

    return "/" . ltrim(substr($path, strlen(DOCUMENT_ROOT)), "/");
  }

  /**
   * Gets the 'absolute path' of a relative path.
   *
   * @param string $path Relative path
   */
  static public function getAbsolutePath($path)
  {
    return SysFile::concat(DOCUMENT_ROOT, $path);
  }
}
