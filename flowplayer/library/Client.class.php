<?php

namespace Flowplayer\Library
{
  require_once('Video.class.php');
  require_once(realpath(dirname(__FILE__) . '/../RequestUnsuccessfullException.class.php'));
  class Client
  {
    public function __construct($flowplayerAuthcode) {
      $this->authCode = $flowplayerAuthcode;
    }

    private function _request($path)
    {
      $baseUrl = 'http://videos.api.dev.flowplayer.org';
      $url = sprintf('%s/%s', $baseUrl, $path);
      $raw = file_get_contents($url, false, stream_context_create(array(
        'http' => array(
          'header' => 'flowplayer-authcode: ' . $this->authCode,
          'ignore_errors' => true
        )
      )));
      $statusCode = $this->_parseStatus($http_response_header[0]);

      $ret = array(
        'success' => $statusCode < 400,
        'status' => $statusCode,
        'body' => json_decode($raw, true)
      );
      return (object)$ret;
    }

    public function listVideos()
    {
      $res = $this->_request('videos');
      if (!$res->success) throw new RequestUnsuccessfullException('Failed to load videos');
      $ret = array();
      foreach($res->body as $row) {
        $ret[] = new Video($row);
      }
      return $ret;
    }

    private function _parseStatus($header)
    {
      $m = array();
      if (preg_match('/HTTP\/\d.\d\ (\d\d\d)\ .*/', $header, $m)) {
        return (int)$m[1];
      }
      return 500;
    }
  }
}