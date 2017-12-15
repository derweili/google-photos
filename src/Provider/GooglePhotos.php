<?php

namespace Derweili\GooglePhotos;


use League\OAuth2\Client\Provider\Google;

/**
 *
 */
class GooglePhotos extends Google
{

  /*
   *  Set default scope
   */
  protected function getDefaultScopes()
  {
      return [
          'email',
          'openid',
          'profile',
          'https://picasaweb.google.com/data/'
      ];
  }

  function setToken( string $token ){
    $this->token = $token;
  }


  function getUsersAlbums( $token ){
    $this->->getAuthenticatedRequest( 'GET', 'https://picasaweb.google.com/data/feed/api/user/default', $token );
  }

}
