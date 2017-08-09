<?php

namespace backend\controllers;

use Yii;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use unirest\Rest_Api;



class EventsController extends Controller
{

    public $controller_name = 'events';
    public $default_user_id = '5612'; 

    /* Create Gallery action */

    public function actionGet_events()
    {    

    	$ajax_action_url = Url::to(['events/ajaxcallback']);
      
      $title = 'Events / Get Events';


      /* Fields */

      $fields = array();

      $fields['action'] = array();
      $fields['action']['value'] = 'get_events';
      $fields['action']['label'] = 'Action';
      $fields['action']['type'] = 'hidden';

      $fields['start_date']['value'] = '2017-01-01';
      $fields['start_date']['label'] = 'start_date';

      $fields['end_date']['value'] = '2017-12-12';
      $fields['end_date']['label'] = 'end_date';

      /*
      $fields['term']['value'] = '';
      $fields['term']['label'] = 'term';
      */


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
          
         /*
          case 'add_media':
            $response = $Rest_Api->mediapress_post_add_media($request_post, $request_files['file']);
            break;
          */

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
