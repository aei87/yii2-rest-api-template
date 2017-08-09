<?php

namespace backend\controllers;

use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use unirest\Rest_Api;



class BuddypressController extends Controller
{

    public $controller_name = 'buddypress';
    public $default_user_id = '5612'; 

    /* Get  topics */

    public function actionGet_topics()
    {    

      $ajax_action_url = Url::to(['buddypress/ajaxcallback']);
      
      $title = 'BuddyPress / Get Topics';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'get_topics';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['search'] = array();
      $fields['search']['value'] = '';
      $fields['search']['label'] = 'search';

      $fields['forum_id'] = array();
      $fields['forum_id']['value'] = '';
      $fields['forum_id']['label'] = 'forum_id (numeric, not group_id)';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }



    /* Create group action */

    public function actionCreate_group()
    {    

    	$ajax_action_url = Url::to(['buddypress/ajaxcallback']);
      
      $title = 'BuddyPress / Create Group';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'create_group';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['creator_id'] = array();
      $fields['creator_id']['value'] = $this->default_user_id;
      $fields['creator_id']['label'] = 'creator_id';

      $fields['name'] = array();
      $fields['name']['value'] = '';
      $fields['name']['label'] = 'name';

      $fields['slug'] = array();
      $fields['slug']['value'] = '';
      $fields['slug']['label'] = 'slug';

      $fields['description'] = array();
      $fields['description']['value'] = '';
      $fields['description']['label'] = 'description';

      $fields['status'] = array();
      $fields['status']['value'] = 'public';
      $fields['status']['label'] = 'status (public, private, hidden)';
                                    
      $fields['enable_forum'] = array();
      $fields['enable_forum']['value'] = '1';
      $fields['enable_forum']['label'] = 'enable_forum';

      $fields['invite_status'] = array();
      $fields['invite_status']['value'] = 'members';
      $fields['invite_status']['label'] = 'invite_status (members, mods, admins)';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
      	'ajax_action_url' => $ajax_action_url,
      	
      ]);       
    }



    /* Invite user action */

    public function actionInvite_user()
    {    

      $ajax_action_url = Url::to(['buddypress/ajaxcallback']);
      
      $title = 'BuddyPress / Invite user';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'invite_user';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['inviter_id'] = array();
      $fields['inviter_id']['value'] = $this->default_user_id;
      $fields['inviter_id']['label'] = 'inviter_id';

      $fields['user_id'] = array();
      $fields['user_id']['value'] = '';
      $fields['user_id']['label'] = 'user_id';

      $fields['group_id'] = array();
      $fields['group_id']['value'] = '';
      $fields['group_id']['label'] = 'group_id';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }




    /* Invite to shetrades action */

    public function actionInvite_shetrades()
    {    

      $ajax_action_url = Url::to(['buddypress/ajaxcallback']);
      
      $title = 'BuddyPress / Invite user to Shetrades';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'invite_shetrades';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['email'] = array();
      $fields['email']['value'] = '';
      $fields['email']['label'] = 'email';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }



    /* Join group action */

    public function actionJoin_group()
    {    

      $ajax_action_url = Url::to(['buddypress/ajaxcallback']);
      
      $title = 'BuddyPress / Join group';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'join_group';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['user_id'] = array();
      $fields['user_id']['value'] = '';
      $fields['user_id']['label'] = 'user_id';

      $fields['group_id'] = array();
      $fields['group_id']['value'] = '';
      $fields['group_id']['label'] = 'group_id';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }




    /* Create topic */

    public function actionCreate_topic()
    {    

      $ajax_action_url = Url::to(['buddypress/ajaxcallback']);
      
      $title = 'BuddyPress / Create Topic';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'create_topic';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['post_author'] = array();
      $fields['post_author']['value'] = $this->default_user_id;
      $fields['post_author']['label'] = 'post_author';

      $fields['post_parent'] = array();
      $fields['post_parent']['value'] = '';
      $fields['post_parent']['label'] = 'post_parent';

      $fields['post_content'] = array();
      $fields['post_content']['value'] = '';
      $fields['post_content']['label'] = 'post_content';
      $fields['post_content']['type'] = 'textarea';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }


    /* Get replies */

    public function actionGet_replies()
    {    

      $ajax_action_url = Url::to(['buddypress/ajaxcallback']);
      
      $title = 'BuddyPress / Get Replies';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'get_replies';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['search'] = array();
      $fields['search']['value'] = '';
      $fields['search']['label'] = 'search';

      $fields['topic_id'] = array();
      $fields['topic_id']['value'] = '';
      $fields['topic_id']['label'] = 'topic_id (numeric)';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }



    /* Send invitation action */

    public function actionSend_invitation()
    {    

      $ajax_action_url = Url::to(['buddypress/ajaxcallback']);

      $title = 'BuddyPress / Send invitation';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'send_invitation';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['title'] = array();
      $fields['title']['value'] = 'Some title...';
      $fields['title']['label'] = 'Title';

      return $this->render('index', [
        
        'title' => $title, 
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }





    public function actionAjaxcallback()
    {    

        $Rest_Api = new Rest_Api();
	    
        $request_post = $_POST;
        $request_files = $_FILES;
       
        switch ($_POST['action']) {
          
          default:
            $response = $Rest_Api->post($this->controller_name, $request_post);
            break;
        }
        


        /* POST data */

        ob_start();

        ?><div class="info">
        $_POST:
        <pre><?php print_r($request_post); ?></pre>
        $_FILES:
        <pre><?php print_r($request_files); ?></pre>
        </div><?php

        $post = ob_get_contents();

        ob_end_clean();


        
        /* Request */

        $request = '<div class="info">'.$response['request'].'<br><br> Params: <br>'.$post.'</div>';



        /* Response */

        ob_start();

        ?><div class="info"><pre><?php print_r($response['response']->body); ?></pre></div><?php

        $response_body = ob_get_contents();

        ob_end_clean();



        /* Response */

        ob_start();

        ?><div class="info"><pre><?php print_r($response['response']); ?></pre></div><?php

        $response = ob_get_contents();

        ob_end_clean();



        echo json_encode(array('request' => $request, 'response_body' => $response_body, 'response' => $response, 'post' => $post)); 
        die();    
    }

}
