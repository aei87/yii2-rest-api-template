<?php

namespace backend\controllers;

use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use unirest\Rest_Api;



class MediapressController extends Controller
{

    public $controller_name = 'mediapress';
    public $default_user_id = '5612'; 

    /* Create Gallery action */

    public function actionCreate_gallery()
    {    

    	$ajax_action_url = Url::to(['mediapress/ajaxcallback']);
      
      $title = 'MemberPress / Create Gallery';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'create_gallery';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['title'] = array();
      $fields['title']['value'] = '';
      $fields['title']['label'] = 'title';

      $fields['description'] = array();
      $fields['description']['value'] = '';
      $fields['description']['label'] = 'description';

      $fields['type'] = array();
      $fields['type']['value'] = 'photo';
      $fields['type']['label'] = 'type';

      $fields['status'] = array();
      $fields['status']['value'] = 'public';
      $fields['status']['label'] = 'status';

      $fields['creator_id'] = array();
      $fields['creator_id']['value'] = $this->default_user_id;
      $fields['creator_id']['label'] = 'creator_id';

      $fields['component'] = array();
      $fields['component']['value'] = 'members';
      $fields['component']['label'] = 'component';

      $fields['component_id'] = array();
      $fields['component_id']['value'] = $this->default_user_id;
      $fields['component_id']['label'] = 'component_id (= creator_id)';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
      	'ajax_action_url' => $ajax_action_url,
      	
      ]);       
    }



    /* Rename Gallery action */

    public function actionRename_gallery()
    {    

      $ajax_action_url = Url::to(['mediapress/ajaxcallback']);
      
      $title = 'MemberPress / Rename Gallery';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'rename_gallery';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['id'] = array();
      $fields['id']['value'] = '';
      $fields['id']['label'] = 'id (= gallery_id)';

      $fields['title'] = array();
      $fields['title']['value'] = '';
      $fields['title']['label'] = 'title';

      $fields['description'] = array();
      $fields['description']['value'] = '';
      $fields['description']['label'] = 'description';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }


    /* Delete  Gallery action */

    public function actionDelete_gallery()
    {    

      $ajax_action_url = Url::to(['mediapress/ajaxcallback']);
      
      $title = 'MemberPress / Delete Gallery';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'delete_gallery';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['gallery_id'] = array();
      $fields['gallery_id']['value'] = '';
      $fields['gallery_id']['label'] = 'gallery_id';

      return $this->render('index', [
      
        'title' => $title,      
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }



    /* Add photo action */

    public function actionAdd_media()
    {    

      $ajax_action_url = Url::to(['mediapress/ajaxcallback']);

      $title = 'MemberPress / Add Media';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'add_media';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['title'] = array();
      $fields['title']['value'] = '';
      $fields['title']['label'] = 'Title';

      $fields['description'] = array();
      $fields['description']['value'] = '';
      $fields['description']['label'] = 'description';

      $fields['user_id'] = array();
      $fields['user_id']['value'] = $this->default_user_id;
      $fields['user_id']['label'] = 'user_id';

      $fields['gallery_id'] = array();
      $fields['gallery_id']['value'] = '';
      $fields['gallery_id']['label'] = 'gallery_id';
      
      $fields['context'] = array();
      $fields['context']['value'] = 'gallery';
      $fields['context']['label'] = 'context';
                
      $fields['component'] = array();
      $fields['component']['value'] = 'members';
      $fields['component']['label'] = 'component';

      $fields['file'] = array();
      $fields['file']['value'] = '';
      $fields['file']['label'] = 'Photo file';
      $fields['file']['type'] = 'file';


      return $this->render('index', [
        
        'title' => $title, 
        'fields' => $fields,
        'ajax_action_url' => $ajax_action_url,
        
      ]);       
    }






    /* Delete Media action */

    public function actionDelete_media()
    {    

      $ajax_action_url = Url::to(['mediapress/ajaxcallback']);
      
      $title = 'MemberPress / Delete Media';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'delete_media';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['media_id'] = array();
      $fields['media_id']['value'] = '';
      $fields['media_id']['label'] = 'media_id';

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
          
          case 'add_media':
            $response = $Rest_Api->mediapress_post_add_media($request_post, $request_files['file']);
            break;

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



        /* Response full */

        ob_start();

        ?><div class="info"><pre><?php print_r($response['response']); ?></pre></div><?php

        $response = ob_get_contents();

        ob_end_clean();



        echo json_encode(array('request' => $request, 'response_body' => $response_body, 'response' => $response, 'post' => $post)); 
        die();    
    }

}
