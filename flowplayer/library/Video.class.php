<?php
namespace Flowplayer\Library
{
  class Video
  {
    private $data;

    public function __construct($data)
    {
      $this->data = $data;
    }

    public function get($key)
    {
      if (array_key_exists($key, $this->data)) {
        return $this->data[$key];
      }
      return null;
    }
  }
}