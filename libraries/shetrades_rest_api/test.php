<?php

namespace Unirest; 

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '256M');

//require_once('Unirest.php');

if (!class_exists('Basic_Rest_Api')) {
  
  class Basic_Rest_Api {
    
    public $username;
    public $password;
    public $host;
    public $path;

    public function __construct () {

        $this->username = 'testadmin';
        $this->password = '!kQb1pTgc8#2gaqnxbw!@*14';
        $this->host = 'http://liveshetrades.fvds.ru';
        $this->path = 'shetrades/v1/custom';
    } 


    /* Execute request */ 

    public function info_xprofile_request($api_url, $params = array()) {    

      $headers = array( 'Authorization' => 'Basic '.base64_encode("$this->username:$this->password") );
      Unirest\Request::defaultHeaders($headers);   
      $result = Unirest\Request::post($api_url, null, $params );

      return json_decode($result->raw_body, true);
    }




    /* Get fields names*/

    public function info_xprofile_fields_names() {    
               
      $api_url = $this->host.'/wp-json/'.$this->path.'/info/get?type=xprofile_fields_names';
      
      return $this->info_xprofile_request($api_url);
    }



    /* Get fields values*/

    public function info_xprofile_fields_values($fields) {
                    
      $api_url = $this->host.'/wp-json/'.$this->path.'/info/get?type=xprofile_fields_values';
      $params = array(
        'fields' => json_encode($fields)
      );

      return $this->info_xprofile_request($api_url, $params);   
    }



    /* Updates values */

    public function info_xprofile_update_fields_values($fields) {
                    
      $api_url = $this->host.'/wp-json/'.$this->path.'/info/update?fields='.json_encode($fields);
      $params = array(
        'fields' => json_encode($fields)
      );

      return $this->info_xprofile_request($api_url, $params);  
    }

  //end of class
  }

}







$Basic_Rest_Api = new Basic_Rest_Api;

$fields = array();

if (isset($_POST['update'])){

  foreach ($_POST as $field_key => $field_value) {
      
      if ($field_key !== 'update') {

        if (is_array($field_value)) {

            foreach ($field_value as $option_key => $option_value) {

              $fields[$field_key][] =  $option_value;
            }
        }
        else {

          $fields[$field_key] = $field_value;
        }
      } 

  }

 $Basic_Rest_Api->info_xprofile_update_fields_values($fields);
}



$info_xprofile_fields_names = array();

$info_xprofile_fields_names = $Basic_Rest_Api->info_xprofile_fields_names();
$info_xprofile_fields_values = $Basic_Rest_Api->info_xprofile_fields_values($info_xprofile_fields_names);


?> 


<form action="" class="repeater" method="post"> 

<?php

foreach ($info_xprofile_fields_names as $field_key => $field_value) {
  
  if (count($field_value['options']) > 0) {

    ?>

      <div class="item"> <h4> <?php echo $field_value['name'].' ['.$field_key.']'; ?> </h4>
        <select style="height:200px; width:400px;" class="item_select" multiple="multiple" name="<?php echo $field_key; ?>[]">
          
        <?php 
          
            foreach ($field_value['options'] as $option_key => $option_value) {

              if (is_array($info_xprofile_fields_values[$field_key])) {
                
                if (in_array($option_value, $info_xprofile_fields_values[$field_key])) { $selected = 'selected';}  else {$selected = '';}
              }
              else {
                
                if ($option_value === $info_xprofile_fields_values[$field_key])  { $selected = 'selected';}  else {$selected = '';}
              }
              
              ?>  <option <?php echo $selected; ?>  value="<?php echo $option_value; ?>"><?php echo $option_value; ?></option>   <?php
            }          

        ?>
   
        </select>
      </div>
    <?php

  }


  else if (count($field_value['csv_options']) > 0) {

    ?>

      <div class="item"> <h4> <?php echo $field_value['name'].' ['.$field_key.']'; ?> </h4>
        <select style="height:200px; width:400px;" class="item_select" multiple="multiple" name="<?php echo $field_key; ?>[]">
          
        <?php 
          
            foreach ($field_value['csv_options'] as $option_key => $option_value) {

              if (is_array($info_xprofile_fields_values[$field_key])) {
                
                if (in_array($option_key, $info_xprofile_fields_values[$field_key])) { $selected = 'selected';}  else {$selected = '';}
              }
              else {
                
                if ($option_value === $info_xprofile_fields_values[$field_key])  { $selected = 'selected';}  else {$selected = '';}
              }
              
              ?>  <option <?php echo $selected; ?>  value="<?php echo $option_key; ?>"><?php echo $option_value; ?></option>   <?php
            }          

        ?>
   
        </select>
      </div>
    <?php

  }


  else {

    if (is_array($info_xprofile_fields_values[$field_key])) {
      $info_xprofile_fields_values[$field_key] = htmlspecialchars(serialize($info_xprofile_fields_values[$field_key]));
    } 
    else{
      $info_xprofile_fields_values[$field_key] = htmlspecialchars($info_xprofile_fields_values[$field_key]);
    }
    
    ?>
      <div class="item"> <h4> <?php echo $field_value['name'].' ['.$field_key.']'; ?> </h4>
         <input type="text" name="<?php echo $field_key; ?>" value="<?php echo $info_xprofile_fields_values[$field_key]; ?>">
      </div>
    <?php

  }

}

?>

<input type="submit" name="update" value="Update fields">

</form>



<style type="text/css">
  
  .item{
    padding:20px;
  }

  .item input{
    margin-top: 5px;
    margin-right:20px;
  }


</style>