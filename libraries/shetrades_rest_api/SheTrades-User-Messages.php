<?php
/*
 * SheTrades-ExportUsers.php
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
 *  2017/06/16  E.Abdullin      Initial implementation
 *
 *  Description:
 *
 *  This class is used to exports members to .csv file
 *    
 *
 */
 

if (!class_exists('SheTrades_ExportUsers')) {
  
  class SheTrades_ExportUsers {
    
    public $filters = array();

    public function __construct () {
             
        // adding actions..
        add_action('admin_menu', array( $this, 'add_menu_option' )); 
        add_action('admin_enqueue_scripts', array( $this, 'email_users_enqueue_scripts' )) ;
        add_action('wp_ajax_export_get_filtered_users', array( $this, 'export_get_filtered_users' ));
    }


    /*
     [ tagjs tagcss tagadd tagscript tagengueue taginclude tagregister tagstyle ]
     * Adds some JS
    */


    public function email_users_enqueue_scripts($hook) {
           
        wp_register_script('selectator', plugin_dir_url( __DIR__ ) . '/js/all.js', array('jquery'), false, true);
        wp_enqueue_script('selectator');

        wp_register_script('shetrades_admin', plugin_dir_url( __DIR__ ) . '/js/shetrades_admin.js', array('jquery'), false, true);
        wp_enqueue_script('shetrades_admin');
        
        wp_register_style('font-awesome', plugin_dir_url( __DIR__ ) . '/css/font-awesome.min.css');
        wp_enqueue_style('font-awesome');

        wp_register_style('ajax_fields', plugin_dir_url( __DIR__ ) . '/css/ajax_fields.css');
        wp_enqueue_style('ajax_fields');

        wp_register_style('export_users', plugin_dir_url( __DIR__ ) . '/css/admin_export_users.css');
        wp_enqueue_style('export_users');

        wp_register_style('ajax_loader', plugin_dir_url( __DIR__ ) . '/css/ajax_loader.css');
        wp_enqueue_style('ajax_loader');

    }



    /*
       [ tagmenu tagadd tagoption ] 
     * Adds a menu option
    */

    public function add_menu_option() {
        
        add_menu_page( 'Export Users', 'Export Users', 'manage_options', 'export_users', array( $this, 'add_menu_page' ), '', 554 );
    }



    /*

       [ tagform tagprocess tagpost tagselectbox tagselect tagexport tagcsv tagbuffer tagob]
         
     * Menu option callback
    */

    public function add_menu_page()
    {

        global $bp, $wpdb; 

        $options = Shetrades_Options::ExportUsers();
        
        $this->filters = $options->filters;
        $this->fields = $options->fields;
        if ((isset($_POST['download'])) && (!empty($_POST['download']))) {
            
            $filename = 'members.csv';
            $user_ids = explode(',', $_POST['user_ids']);
            $result = array();    

            foreach ($user_ids as $key => $user_id) {
                
                $result[$key]['id'] = '"'.$user_id.'"';
                
                foreach ($this->fields as $field_id => $field_value) {
                
                    $value = $wpdb->get_col("
                                            SELECT value FROM {$bp->profile->table_name_data}
                                            WHERE field_id = ".$field_value."
                                            AND user_id = '".$user_id."' 
                                            ");     

                    $result[$key][$field_value] = '"'.$value[0].'"';
                }
            }

            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'";');

            ob_end_clean();

            $f = fopen('php://output', 'w');            
            
            foreach ($result as $fields) {

                fwrite($f, implode(',', $fields) . PHP_EOL); 
            }

            fclose($f);

            exit();

        }

        $this->users_form();
    }




    /*
     [ tagform taguser tagusers tagjs tagajax tagdynamic tagdynamically tagcreate tagnonce tagload tagerror tagerrors tagui tagui1 tagadmin tagpage tagpage1 tagadmin1 tagexport tagloader tagspinner]
     * Menu option callback
    */

    public function users_form()
    {

        ?>

        <div class="wrap export_users">
            <div id="icon-users" class="icon32"><br/></div>

            <h2 class="nav-tab-wrapper">
                <a href="http://liveshetrades.fvds.ru/wp-admin/admin.php?page=export_users" class="nav-tab nav-tab-active">Export users</a>
                <a href="http://liveshetrades.fvds.ru/wp-admin/admin.php?page=send_emails" class="nav-tab">Send Emails</a>
            </h2>

            <!-- Ajax Errors / Notices -->

            <form name="ExportUsers" action="" method="post">
            
                <div id="message_wrapper">
                    <div class="ps_notice" id="user_notice">Please choose at least one filter..</div> 
                    <div class="ps_info ps_notice" id="response"></div>
                    <div class="ps_error ps_notice" id="errors"></div>
                </div>

                <h2 class="h2"><?php echo 'Exports members'; ?></h2>

                <?php wp_nonce_field( 'mailusers_send_to_group', 'mailusers_send_to_group_nonce' ); ?>

                <div>

	               <div class="right_col">

                        <table class="table_filter" width="100%" cellspacing="2" cellpadding="5">
                            
                            <script type="text/javascript">

                                var ajax_nonce = '<?php echo wp_create_nonce("nonce_for_admin_get_users"); ?>';

                                // some tricky converting
                                var filters = JSON.parse('<?php echo str_replace("\u0022","\\\\\"", str_replace('\n', "", json_encode($this->filters, JSON_HEX_QUOT))); ?>');

                            </script>

                            <?php
                            foreach ($this->filters as $key => $filter) {
                                ?>
                                <tr>
                                    <th scope="row" class="label" valign="top"><label><?php _e($filter['label'] , 'shetrades_theme'); ?>
                                        <br/><span class="label_small"><small>ID = "<?php echo $filter['id']; ?>" <br> </small></span></label>
                                    </th>                                     
                                    <td>
                                      <?php echo $filter['value']; ?>
                                    </td>
                                </tr>
                                <?php
                            } 
                            ?>

                            <tr>
                                <td class="filter_submit"><input class="button-primary" id="export_filter_submit" type="submit" name="Filter" value="Filter members" /></td>
                            </tr>
                        </table>            
                    </div>

                    <div class="left_col">
                        Click to download .csv file with filtered members
                        <div id="users"></div>
                        <p class="submit">
                            <input disabled class="button-primary" id="download" type="submit" name="download" value="Download .csv" />
                        </p>
                    </div>


                </div>
               
            </form>
        </div>

        <div class="loader" style="display: none;">
            <div class="cube-wrapper">
                <div class="cube-folding">
                    <span class="leaf1"></span>
                    <span class="leaf2"></span>
                    <span class="leaf3"></span>
                    <span class="leaf4"></span>
                </div>
                <span class="loading" data-name="Loading">Loading</span>
            </div>
        </div>
  
        <?php
    }




    /**
    [ tagajax tagnonce tagcheck tagcallback tagarray tagterm tagget tagselect tagbuffer tagob]

     * Get the users
    */


    function export_get_filtered_users() {

        check_ajax_referer( 'nonce_for_admin_get_users', 'security' );
        
        global $wpdb;
        $select_values = $_POST['request_data'];

        $error = false;
        $response = false;
        $users = false;

        $users_new = SheTrades_Xprofile_Hooks::get_users_by_codes($select_values);

        if (count($users_new) == 0) {
            $error = 'Nothing was found';
        }


        ob_start();
        ?> 
        <input type="hidden" name="user_ids" value="<?php echo implode(',', $users_new); ?>"> 
        <?php
        $users = ob_get_contents();
        ob_end_clean();
         

        ob_start();   
        ?> 
        <b><?php echo $count = count($users_new); ?> entries</b> was / were found
        <?php
        $response = ob_get_contents();
        ob_end_clean();


        echo(json_encode( array('error' => $error, 'response' => $response, 'users' => $users) ));
        wp_die();
        
    }

   

 }      
}






























