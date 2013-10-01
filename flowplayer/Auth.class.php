<?php

namespace Flowplayer
{
  require_once('RequestUnsuccessfullException.class.php');

  class Auth
  {
    private static function _request($path, $parameters = array())
    {
      $baseUrl = 'http://account.api.dev.flowplayer.org';

      $parameters['_format'] = 'json';

      $url = sprintf('%s/%s?%s', $baseUrl, $path, http_build_query($parameters));

      $rawBody = file_get_contents($url);
      return json_decode($rawBody);
    }

    public static function authenticate($username, $password)
    {
      $tmp = self::_request('auth');
      if (!$tmp->success) throw new RequestUnsuccessfullException('Failed to obtain seed');
      $seed = $tmp->result;
      $tmp = self::_request('auth', array(
        'username' => $username,
        'hash' => sha1($username . $seed . sha1($password)),
        'seed' => $seed
      ));
      if (!$tmp->success) throw new RequestUnsuccessfullException('Incorrect login');
      return $tmp->result->authcode;
    }
  }
}