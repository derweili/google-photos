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


  function getUsersAlbums( $token = null ){

    $token = $token ? $token : $this->token;

    $this->->getAuthenticatedRequest( 'GET', 'https://picasaweb.google.com/data/feed/api/user/default', $token );
  }



  // xml parse response
  private function parsePhotoResponse( $response ){

    // get body as string from response
    $content = (string) $response->getBody();

    // build an object from xml
    $xml_object = new SimpleXMLElement($content);

    return $xml_object;

  }



  function get_users_album_list( $token = null,  $user = 'default'){

  }


}
