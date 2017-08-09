<?php
/*
 * SheTrades-Rest-Api.php
 *
 * Copyright (C) 2016, ITC (International Trade Centre). all rights reserved.
 *
 *
 * No part of this software may be reproduced in any form or by any means
 * - graphic, electronic or mechanical, including photocopying, recording,
 * taping or information storage and retrieval systems -
 * except with the written permission of ITC (International Trade Centre).
 *
 *
 * This notice may not be removed.
 *
 *  History:
 *
 *  Modified:   By:             Reason:
 *  ---------   ---             -------
 *  2016/07/17  R.Vidal         Initial implementation
 *
 *  Description:
 *
 *  This class is used to add some REST API functionality for mob app
 *    
 *
 */
 
require_once( 'class-wp-rest-controller.php' );
require_once( 'plugin.php' );
 
if (!class_exists('SheTrades_Rest_Api')) {
  
  class SheTrades_Rest_Api {
    
    public static $currentUser = null;
    
    private $endPoints = array(
        'member'                        => 'SheTrades_Rest_Api_Member',
        'thread'                        => 'SheTrades_Rest_Api_Thread',
        'notification'                  => 'SheTrades_Rest_Api_Notification',
        'friend'                        => 'SheTrades_Rest_Api_Friend',
        'custom/field/certification'    => 'SheTrades_Rest_Api_Custom_Fields_Type_Certifications',
        'custom/field/country'          => 'SheTrades_Rest_Api_Custom_Fields_Type_Countries',
        'custom/field/customer'         => 'SheTrades_Rest_Api_Custom_Fields_Type_Customers',
        'custom/field/product'          => 'SheTrades_Rest_Api_Custom_Fields_Type_Products',
        'custom/field/service'          => 'SheTrades_Rest_Api_Custom_Fields_Type_Services',
        'custom/field/verifier'         => 'SheTrades_Rest_Api_Custom_Fields_Type_Verifiers',
        'custom/info'                   => 'SheTrades_Rest_Api_Custom_Endpoint_Info',
        'custom/mediapress'             => 'SheTrades_Rest_Api_Custom_Endpoint_Mediapress',
        'custom/buddypress'             => 'SheTrades_Rest_Api_Custom_Endpoint_Buddypress',
        'custom/events'             => 'SheTrades_Rest_Api_Custom_Endpoint_Events',
    );
    
  
    public function __construct () {
      
      self::$currentUser = null; 
      
      /** Buddypress hook **/
      add_action( 'bp_init', array($this, 'init') );
      
      /** Buddypress Rest API Init **/
      add_action( 'bp_rest_api_init', array($this, 'bp_rest_api_init') );
      
      add_filter( 'determine_current_user', array($this, 'determine_current_user'), 20 );
      
    }

    /**
     * Custom initialization
     *
     */
    public function init() {
      require_once( 'SheTrades-Rest-Api-Member.php' );
      require_once( 'SheTrades-Rest-Api-Thread.php' );
      require_once( 'SheTrades-Rest-Api-Notification.php' );
      require_once( 'SheTrades-Rest-Api-Friend.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Fields-Type-Certifications.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Fields-Type-Countries.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Fields-Type-Customers.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Fields-Type-Products.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Fields-Type-Services.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Fields-Type-Verifiers.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Endpoint-Info.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Endpoint-Mediapress.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Endpoint-Buddypress.php' );
      require_once( 'SheTrades-Rest-Api-Custom-Endpoint-Events.php' );
    }
    
    /**
     * Register all Our endpoints.
     *
     */
    public function bp_rest_api_init() {
      foreach ($this->endPoints as $endPoints) {
        if (class_exists ($endPoints)) {
          $controller = new $endPoints;
          $controller->register_routes();
        }
        
      }
    }
    
    /**
     * Provide Basic Auth for REST API.
     *
     * curl --user admin:password http://shetrades/wp-json/
     */
    public function determine_current_user( $user ) {
//      global $wp_json_basic_auth_error;
//      $wp_json_basic_auth_error = null;
      // Don't authenticate twice
      if ( ! empty( $user ) ) {
        return $user;
      }

      // Check that we're trying to authenticate
      if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
        return $user;
      }
      
      $username = $_SERVER['PHP_AUTH_USER'];
      $password = $_SERVER['PHP_AUTH_PW'];
      
      /**
       * In multi-site, wp_authenticate_spam_check filter is run on authentication. This filter calls
       * get_currentuserinfo which in turn calls the determine_current_user filter. This leads to infinite
       * recursion and a stack overflow unless the current function is removed from the determine_current_user
       * filter during authentication.
       */
      remove_filter( 'determine_current_user', array($this, 'determine_current_user'), 20 );
      $user = wp_authenticate( $username, $password );
      add_filter( 'determine_current_user', array($this, 'determine_current_user'), 20 );
      if ( is_wp_error( $user ) ) {
        return null;
      }
      self::$currentUser = $user;
      return $user->ID;
    }
    
    public static function is_user_logged() {
      $currentUser = self::get_current_user();
      
      return empty($currentUser) ? false : true;
    }
    
    public static function get_current_user() {
      
      return self::$currentUser;
    }
    
  }
}

