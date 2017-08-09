<?php

/*

 */

require_once( 'SheTrades-Rest-Api-Base.php' );
  
if (!class_exists('SheTrades_Rest_Api_Custom_Endpoint_Buddypress'))
{
  class SheTrades_Rest_Api_Custom_Endpoint_Buddypress extends SheTrades_Rest_Api_Base {
    
    protected $customField;
    
    public function __construct() {

      parent::__construct(); 
      $this->rest_base = 'custom/buddypress'; 
    }

    /**
     * Register the plugin routes.
     *
     */

    public function register_routes() {  

      register_rest_route( $this->namespace, '/' . $this->rest_base.'/', array(

        array(
          'methods'             => 'POST',
          'callback'            => array( $this, 'group' ),
          'permission_callback' => array( $this, 'group_permissions_check' ),
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
    public function group($request) {

      $data = $_POST; 
      unset($data['action']);
      


      /* create a new group */

      if ($_GET['action'] == 'create_group') {
  
        $args = array();
        
        $id = groups_create_group($data);

        if ($data['enable_forum'] === '1') {
          
          switch ( $data['status'] ) {
            case 'hidden'  :
              $status = bbp_get_hidden_status_id();
              break;
            case 'private' :
              $status = bbp_get_private_status_id();
              break;
            case 'public'  :
            default        :
              $status = bbp_get_public_status_id();
              break;
          }

          // Create the initial forum
          $forum_id = bbp_insert_forum( array(
            'post_parent'  => bbp_get_group_forums_root_id(),
            'post_title'   => $data['name'],
            'post_content' => $data['description'],
            'post_status'  => $status,
            'post_author'  => $data['creator_id']
          ) );

   
          $BBP_Forums_Group_Extension = new BBP_Forums_Group_Extension();
          
          $BBP_Forums_Group_Extension->new_forum( array( 'forum_id' => $forum_id, 'group_id' => $id ) );
          groups_update_groupmeta($id, '_bbp_forum_enabled_' . $forum_id, true );
          $BBP_Forums_Group_Extension->toggle_group_forum($id, true );
        }

        groups_update_groupmeta( $id, 'invite_status', $data['invite_status'] );
     
        if ($id > 0){
         
          $result = 'Group id => "'.$id.'" was created. Forum id: "'.$forum_id.'"';                 
        }
      }

    

      /* Invite user to a group */

      else if ($_GET['action'] == 'invite_user') {
          
        $args = array();

        if ((isset($data['user_id'])) && (!empty($data['user_id']))){
          $args['user_id'] = $data['user_id'];
        } else {
          return 'please enter "user_id"..';
          die();
        }

        if ((isset($data['group_id'])) && (!empty($data['group_id']))){
          $args['group_id'] = $data['group_id'];
        } else {
          return 'please enter "group_id"..';
          die();
        }

        if ((isset($data['inviter_id'])) && (!empty($data['inviter_id']))){
          $args['inviter_id'] = $data['inviter_id'];
        } else {
          return 'please enter "inviter_id"..';
          die();
        }

        if (groups_invite_user($args)){
          $a = groups_send_invites($args['inviter_id'], $args['group_id']);
        }

        ob_start();

        ?><pre><?php print_r($a); ?></pre><?php

        $result = ob_get_contents();
        ob_end_clean();     

        $result = 'User id = "'.$args['user_id'].'" was invited';
      }



      /* Invite user to shetrades */

      else if ($_GET['action'] == 'invite_shetrades') {
         
        
        $args = array(
          
          /*
          'tokens' => array(
                'site.name' => '...',
            ),
            */
        );
        

        $data = $this->has_empty($data);

        $result = bp_send_email('send_shetrades_invitation', $data['email'], $args);

        if ($result <> 1) {

        ob_start();
        ?><pre><?php print_r($result); ?></pre><?php
        $result = ob_get_contents();
        ob_end_clean();     

        } else {

          $result = 'Email = "'.$data['email'].'" was invited';
        }  
      }


  
      /* Join to a group */

      else if ($_GET['action'] == 'join_group') {
          
        $args = array();

        if ((isset($data['user_id'])) && (!empty($data['user_id']))){
          $args['user_id'] = $data['user_id'];
        } else {
          return 'please enter "user_id"..';
          die();
        }

        if ((isset($data['group_id'])) && (!empty($data['group_id']))){
          $args['group_id'] = $data['group_id'];
        } else {
          return 'please enter "group_id"..';
          die();
        }

        $a = groups_join_group($args['group_id'], $args['user_id']);
        
        ob_start();

        ?><pre><?php print_r($a); ?></pre><?php

        $result = ob_get_contents();
        ob_end_clean();     

        $result = 'User id = "'.$args['user_id'].'" joined group id = "'.$args['group_id'].'"';
      }




      /* Get topics */

      else if ($_GET['action'] == 'get_topics') {
          
        $args = array(
          'posts_per_page' => -1, 
          'max_num_pages' => -1, 
          'order' => 'DESC'
        );

        if ((isset($data['forum_id'])) && (!empty($data['forum_id']))){
          $args['post_parent'] = $data['forum_id'];
        }

        if ((isset($data['search'])) && (!empty($data['search']))){
          $args['s'] = $data['search'];
        }

        bbp_has_topics($args);

        ob_start();

        ?><pre><?php print_r(bbpress()->topic_query); ?></pre><?php

        $result = ob_get_contents();
        ob_end_clean();     
      }

  
      /* get replies */

      else if ($_GET['action'] == 'get_replies') {
          
        $args = array(
          'posts_per_page' => -1, 
          'max_num_pages' => -1, 
          'order' => 'DESC'
        );

        if ((isset($data['topic_id'])) && (!empty($data['topic_id']))){
          $args['post_parent'] = $data['topic_id'];
        }

        if ((isset($data['search'])) && (!empty($data['search']))){
          $args['s'] = $data['search'];
        }

        bbp_has_replies($args);

        ob_start();

        ?><pre><?php print_r(bbpress()->reply_query); ?></pre><?php

        $result = ob_get_contents();
        ob_end_clean();     
      }



      /* add a new topic */

      else if ($_GET['action'] == 'create_topic') {
          
        $args = array(
          'posts_per_page' => -1, 
          'max_num_pages' => -1, 
          'order' => 'DESC'
        );
    
        $args_meta = array();    

        if ((isset($data['post_author'])) && (!empty($data['post_author']))){
          $args['post_author'] = $data['post_author'];
          //$args_meta['author_ip'] = preg_replace( '/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR'] );
        } else {
          return 'please enter "post_author"..';
          die();
        }
          
        if ((isset($data['post_parent'])) && (!empty($data['post_parent']))){
          $args['post_parent'] = $data['post_parent'];
          $args_meta['forum_id'] = $data['post_parent'];
        } else {
          return 'please enter "post_parent"..';
          die();
        }

        if ((isset($data['post_content'])) && (!empty($data['post_content']))){
          $args['post_content'] = $data['post_content'];
        } else {
          return 'please enter "post_content"..';
          die();
        }

        $result = bbp_insert_topic($args, $args_meta);
        $result = 'The topic id = "'.$result.'" was created';
      
      }

      return $result;
      die();
    }
    




    /**
     * Check if a given request has access to request items.
     * We do not request any login / password to access to this field
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    
    public function group_permissions_check( $request ) {
      
      return true;
    } 



  }
}





