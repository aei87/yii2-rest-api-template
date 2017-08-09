<?php

/*

 */

require_once( 'SheTrades-Rest-Api-Base.php' );
  
if (!class_exists('SheTrades_Rest_Api_Custom_Endpoint_Events'))
{
  class SheTrades_Rest_Api_Custom_Endpoint_Events extends SheTrades_Rest_Api_Base {
    
    protected $customField;
    
    public function __construct() {

      parent::__construct(); 
      $this->rest_base = 'custom/events';  
    }

    /**
     * Register the plugin routes.
     *
     */

    public function register_routes() {  

      register_rest_route( $this->namespace, '/' . $this->rest_base.'/', array(

        array(
          'methods'             => 'POST',
          'callback'            => array( $this, 'events' ),
          'permission_callback' => array( $this, 'events_permissions_check' ),
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



  /* Check if array has fields with values */

    public function has_values($data) {

      $result = array();

      foreach ($data as $key => $value) {
        
        if ((isset($value)) && (!empty($value))){
           
          $result[$key] = $value;
        }
      }
      
      return $result;
    }



    /**
     * gallery.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Request List of thread object data.
     */
    public function events($request) {

       $data = $_POST; 
       unset($data['action']);

                 
      /* Create gallery */

      if ($_GET['action'] == 'get_events') {
    
        $args = $this->has_values($data); 

        /* 
        'eventDisplay' => 'upcoming',
        'tax_query' => array(array(
                'taxonomy' => TribeEvents::TAXONOMY,
                'field' => 'slug',
                'terms' => 'my-category'))
        */

        unset($args['term']);        
        $events = tribe_get_events($args);

        foreach ($events as $key => $value) {
               
          $events[$key]->post_content = '<b>hidden in short version..</b>';
        }      

        ob_start();
        ?><pre><?php print_r($events); ?></pre><?php
        $result = ob_get_contents();
        ob_end_clean(); 

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
    
    public function events_permissions_check( $request ) {
      
      return true;
    } 


  }
}





