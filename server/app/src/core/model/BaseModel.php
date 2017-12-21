<?php
namespace petitphotobox\core\model;

abstract class BaseModel
{

  /**
   * Gets a plain object representing the current instance.
   *
   * @return object Plain object
   */
  abstract public function toObject();
}
