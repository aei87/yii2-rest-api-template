<?php

namespace unirest; 

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '256M');

if (!class_exists('Rest_Api')) {
  
  class Rest_Api {
    
    public $username;
    public $password;
    public $host;
    public $path;

    public function __construct () {

        $this->username = 'testadmin';
        $this->password = '';
        $this->host = 'http://liveshetrades.fvds.ru';
        $this->path = 'shetrades/v1/custom';
    } 


    /* Execute request */ 

    public function execute_request($api_url, $body = array()) {    

      $headers = array( 'Authorization' => 'Basic '.base64_encode("$this->username:$this->password") );  
      $result = Request::post($api_url, $headers, $body);

      return $result;
    }




    /* Mediapress create gallery */

    public function post($controller_name, $fields) {    
               
      $api_url = $this->host.'/wp-json/'.$this->path.'/'.$controller_name.'?action='.$fields['action'];

      $result['request'] = $api_url;
      $result['response'] = $this->execute_request($api_url, $fields);

      return $result;
    }



    /* Mediapress add photo */

    public function mediapress_post_add_media($fields, $file) {    

      $api_url = $this->host.'/wp-json/'.$this->path.'/mediapress?action=add_media';

      $body = array();
      
      if (empty($file['error'])) {
        
        $body = array(
          'files[_mpp_file]' => Request\Body::File($file['tmp_name'], $file['type'], $file['name'])
        );
      }

      foreach ($fields as $key => $value) {
        $body[$key] = $value;
      }

      $result['request'] = $api_url;
      $result['response'] = $this->execute_request($api_url, $body);

      return $result;
    }


  //end of class
  }

}
?>