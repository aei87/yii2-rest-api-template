<?php
/*
 * SheTrades-Rest-Api-Base.php
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
 *  2016/05/01  R.Vidal         Initial implementation
 *
 *  Description:
 *
 *  This is a generic class that is share with all SheTrades API subclasses
 *    
 *
 */
if (!class_exists('SheTrades_Rest_Api_Xprofile'))
{
  class SheTrades_Rest_Api_Base extends WP_REST_Controller {
    
    public function __construct() {
      $this->namespace = 'shetrades/v1';
    }
    
    /**
     * Check if a given request has access to get information about a specific item.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return bool
     */
    public function get_item_permissions_check( $request ) {
      
      return $this->get_items_permissions_check( $request );
    }

    /**
     * Check if a given request has access to request items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
      
      if (!$this->isUserLogged()) {

        return new WP_Error( 'Unauthorized', 'Your are not authorized to request the url', array( 'status' => 401 ) );
      }
      return true;
    }
    
    /**
     * Check if a given request has access to create a item.
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function create_item_permissions_check( $request ) {

      if (!$this->isUserLogged()) {

        return new WP_Error( 'Unauthorized', 'Your are not authorized to request the url', array( 'status' => 401 ) );
      }
      
      return true;
    }      
    
    /**
     * Check if a given request has access to update a item.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function update_item_permissions_check( $request ) {
      
      if (!$this->isUserLogged()) {

        return new WP_Error( 'Unauthorized', 'Your are not authorized to request the url', array( 'status' => 401 ) );
      }
      return true;
    }
    
    /**
     * Check if a given request has access to delete a item.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function delete_item_permissions_check( $request ) {
      
      if (!$this->isUserLogged()) {

        return new WP_Error( 'Unauthorized', 'Your are not authorized to request the url', array( 'status' => 401 ) );
      }
      return true;
    }
    
    /**
     * Be sure that we have a logged user before accepting the request
     *
     * @return true / false.
     */
    protected function isUserLogged() {
      
      return SheTrades_Rest_Api::is_user_logged();
    }
    
    /**
     * Convert the input date to RFC3339 format.
     *
     * @param string $date_gmt
     * @param string|null $date Optional. Date object.
     * @return string|null ISO8601/RFC3339 formatted datetime.
     */
    protected function convertDateToRFC3339( $date_gmt, $date = null ) {
      if ( isset( $date ) ) {
        return mysql_to_rfc3339( $date );
      }

      if ( $date_gmt === '0000-00-00 00:00:00' ) {
        return null;
      }

      return mysql_to_rfc3339( $date_gmt );
    }
    
    /**
     * Clean up an array, comma- or space-separated list of Strings.
     *
     * @param array|string $list List of ids.
     * @return array Sanitized array of IDs.
     */
    function sanitize_string_list( $list ) {
      if ( !is_array($list) )
        $list = preg_split('/[\s,]+/', $list);
      
      return array_unique( array_map( 'sanitize_text_field', $list) );
    }
     
    /**
     * Validate a request argument based on details registered to the route.
     * Add support for Enum with string comma
     *
     * @param array|string $list List of ids.
     * @return array Sanitized array of IDs.
     */
    function validate_callback( $value, $request, $param ) {
      
      $attributes = $request->get_attributes();
      if ( ! isset( $attributes['args'][ $param ] ) || ! is_array( $attributes['args'][ $param ] ) ) {
        return true;
      }
      $args = $attributes['args'][ $param ];
      
      if (is_array($value) &&  ! empty( $args['enum'] ) ) {
        foreach ($value as $val) {
          if ( ! in_array( $val, $args['enum'] ) ) {
            return new WP_Error( 'rest_invalid_param', sprintf( __( '%s is not one of %s' ), $val, implode( ', ', $args['enum'] ) ) );
          }
        }
      } else {
        return rest_validate_request_arg( $value, $request, $param );
      }
      
    }
    
  }
}