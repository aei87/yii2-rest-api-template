<div class="container inner">

    <div class="col-md-6 mb-4">
        
        <form id="data" method="post" enctype="multipart/form-data">

            <div class="card">
                                   
                <div class="card-header">
                    <i class="fa fa-edit"></i><?php echo $title; ?>
                    <div class="card-actions">
                        <a href="#" class="btn-setting"><i class="icon-settings"></i></a>
                        <a href="#" class="btn-minimize"><i class="icon-arrow-up"></i></a>
                        <a href="#" class="btn-close"><i class="icon-close"></i></a>
                    </div>
                </div>

                <div class="card-block">

                    <?php

                        $ajax_field_ids = array();

                        foreach ($fields as $field_id => $field) {
                            
                            $ajax_field_ids[] =  $field_id;
                            ?>
                                <div class="row">

                                    <div class="col-sm-12">

                                        <div class="form-group">

                                        <?php


                                            if ((isset($field['type'])) && ($field['type'] === 'file')) {

                                                ?>
                                                    <label for="<?php echo $field_id; ?>"><?php echo $field['label']; ?></label> 
                                                    <input type="file" placeholder="Enter value" id="file" name="file" value="<?php echo $field['value']; ?>">
                                                    
                                                <?php
                                            }  
                                            else if ((isset($field['type'])) && ($field['type'] === 'hidden')) {

                                                ?>
                                                    <input type="hidden" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" value="<?php echo $field['value']; ?>">
                                                    
                                                <?php
                                            }   
                                            else if ((isset($field['type'])) && ($field['type'] === 'textarea')) {

                                                ?>
                                                    <label for="<?php echo $field_id; ?>"><?php echo $field['label']; ?></label> 
                                                    <textarea class="form-control" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>"><?php echo $field['value']; ?></textarea>
                                                    
                                                <?php
                                            }                                          
                                            else {

                                                ?>
                                                    <label for="<?php echo $field_id; ?>"><?php echo $field['label']; ?></label>
                                                    <input class="form-control" type="text" placeholder="Enter value" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" value="<?php echo $field['value']; ?>">
                                                <?php
                                            }    
                                           

                                        ?>
                                           

                                        </div>


                                    </div>

                                </div>

                            <?php

                        }

                    ?>
                    <!--/.row-->
                  
                </div>

                <div class="card-footer">
                    <button type="submit" id="submit" name="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Submit</button>
                </div>

            </div>

        </form>

    </div>

    <div class="col-md-6 mb-4">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#request" role="tab" aria-controls="home" aria-expanded="false"><i class="icon-options-vertical"></i> Request &nbsp;<span class="badge badge-success">info</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#response_body" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-options-vertical"></i> Response &nbsp;<span class="badge badge-pill badge-danger">info</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#response" role="tab" aria-controls="profile" aria-expanded="false"><i class="icon-options-vertical"></i> Full Response &nbsp;<span class="badge badge-pill badge-danger">info</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#post" role="tab" aria-controls="messages" aria-expanded="true"><i class="icon-options-vertical"></i> POST &nbsp;<span class="badge badge-pill badge-danger">info</span> </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane" id="request" role="tabpanel" aria-expanded="false">

            </div>
            <div class="tab-pane" id="response" role="tabpanel" aria-expanded="false">

            </div>
            <div class="tab-pane" id="response_body" role="tabpanel" aria-expanded="false">

            </div>
            <div class="tab-pane active" id="post" role="tabpanel" aria-expanded="true">

            </div>
        </div>
    </div>

</div>







<script type="text/javascript">

    var fields = JSON.parse('<?php echo json_encode($ajax_field_ids); ?>');
    var ajax_action_url = '<?php echo $ajax_action_url; ?>';

</script>


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
