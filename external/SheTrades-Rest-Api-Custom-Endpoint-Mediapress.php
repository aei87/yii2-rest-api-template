<?php

/*

 */

require_once( 'SheTrades-Rest-Api-Base.php' );
  
if (!class_exists('SheTrades_Rest_Api_Custom_Endpoint_Mediapress'))
{
  class SheTrades_Rest_Api_Custom_Endpoint_Mediapress extends SheTrades_Rest_Api_Base {
    
    protected $customField;
    
    public function __construct() {

      parent::__construct(); 
      $this->rest_base = 'custom/mediapress';  
    }

    /**
     * Register the plugin routes.
     *
     */

    public function register_routes() {  

      register_rest_route( $this->namespace, '/' . $this->rest_base.'/', array(

        array(
          'methods'             => 'POST',
          'callback'            => array( $this, 'gallery' ),
          'permission_callback' => array( $this, 'gallery_permissions_check' ),
          'args'                => $this->get_collection_params(),
        ),
      ) );
   
    }


    /* Check if array has empty fields */

    public function has_empty($data) {

      foreach ($data as $key => $value) {
        
        if ((!isset($value)) || (empty($value))){
           
          echo 'please enter "'.$key.'"..';
          die(); 
        }
      }
      return $data;
    }




    /**
     * gallery.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Request List of thread object data.
     */
    public function gallery($request) {

       $data = $_POST; 
       unset($data['action']);

                 
      /* Create gallery */

      if ($_GET['action'] == 'create_gallery') {
    
        $data = $this->has_empty($data);  
        $gallery_id = mpp_create_gallery($data);

        if ($gallery_id > 0) $result = 'Gallery id => "'.$gallery_id.'" was created';
      }



      /* Rename gallery */

      if ($_GET['action'] == 'rename_gallery') {
    
        $data = $this->has_empty($data);  
        $gallery_id = mpp_update_gallery($data);
        
        if ($gallery_id > 0) $result = 'Gallery id => "'.$gallery_id.'" was renamed';
      }



      /* Delete gallery */

      if ($_GET['action'] == 'delete_gallery') {
    
        $data = $this->has_empty($data);  
        
        if (mpp_delete_gallery($data['gallery_id'])) {
          $result = 'Gallery id => "'.$data['gallery_id'].'" was deleted';
        } else {
          $result = 'error';
        }   
      }



      /* Delete photo */

      if ($_GET['action'] == 'delete_media') {
    
        $data = $this->has_empty($data);  

        if (mpp_delete_media($data['media_id'])) {
          $result = 'Photo id => "'.$data['media_id'].'" was deleted';
        } else {
          $result = 'error';
        } 
      }



      /* Create photo */

      else if ($_GET['action'] == 'add_media') {

        if (empty($_POST['title'])) {
          die('please enter title...');
        }

        if (empty($_POST['description'])) {
          die('please enter description...');
        }

        if (empty($_POST['gallery_id'])) {
          die('please enter gallery_id...');
        }

        if (empty($_FILES)) {
          die('please include file...');
        }


        // some tricky modifying..

        $_FILES = $_FILES['files'];

        $temp = array();

        foreach ($_FILES['name'] as $option_id => $option_value) {

                $temp[$option_id] = array();
                
                $temp[$option_id]['name'] = $_FILES['name'][$option_id];
                $temp[$option_id]['type'] = 'image/jpeg';
                $temp[$option_id]['tmp_name'] = $_FILES['tmp_name'][$option_id];
                $temp[$option_id]['error'] = $_FILES['error'][$option_id];
                $temp[$option_id]['size'] = $_FILES['size'][$option_id];
        }

        $_FILES = $temp;

        $uploader = mpp_get_storage_manager();

        //check if the server can handle the upload?
        if (!$uploader->can_handle()) die('Server can not handle this much amount of data. Please upload a smaller file or ask your server administrator to change the settings');

        $uploaded = $uploader->upload($_FILES, array( 'file_id' => '_mpp_file', 'gallery_id' => '12273', 'component' => 'members', 'component_id' => '5608', 'is_cover' => 1 ) );

        $args = array(
          'title'       => $_POST['title'],
          'description'   => $_POST['description'],
          'gallery_id'    => $_POST['gallery_id'],
          'user_id'     => $_POST['user_id'],
          'is_remote'     => false,
          'type'        => 'photo',
          'mime_type'     => $uploaded['type'],
          'src'       => $uploaded['file'],
          'url'       => $uploaded['url'],
          'status'      => 'public',
          'comment_status'  => 'open',
          'storage_method'  => 'local',
          'component_id'    => $_POST['user_id'],
          'component'     => 'members',
          'context'     => 'gallery',
          'is_orphan'     => 0,
          'is_cover'      => true
        );

        $photo_id = mpp_add_media($args);
        
        if ($photo_id > 0){
          $result[] = 'Photo id => "'.$photo_id.'"" was upload to Gallery id => "'.$args['gallery_id'].'"';
        }

      }

      return $result;
         
    }
    




    /**
     * Check if a given request has access to request items.
     * We do not request any login / password to access to this field
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    
    public function gallery_permissions_check( $request ) {
      
      return true;
    } 


  }
}





