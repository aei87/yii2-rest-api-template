
/* Export tab. Filter users */

jQuery('form').submit(function(e){
   
  e.preventDefault();

  var ajax_nonce = ''; 
  var request = new FormData(this);
  
  yii_ajax_request(ajax_action_url, ajax_nonce, 'errors', request);


});


  /* Ajax UI proccess */

  function yii_ajax_request(action, nonce, error, request, success_callback = false, error_callback = false) {

   

      jQuery.ajax({ 
          
          type: "POST", 
          url: action, 
          //security: nonce,
          data: request,
          
          processData: false,
          contentType: false,
          
          beforeSend: function() { 

              jQuery('.loader').fadeIn(300);

          },

          success: function(response) { 

              response = jQuery.parseJSON(response);
              jQuery('.loader').fadeOut(500);

              setTimeout(function() {
               
                  $('.nav-tabs a[href="#response_body"]').tab('show'); 
                  if (response['error']) {

                      if (jQuery.isFunction(error_callback)) {
                          error_callback();
                      }
                  }
                  else{

                      if (jQuery.isFunction(success_callback)) {
                          success_callback();
                      }

                      $('#request').html(response['request']);
                      $('#response_body').html(response['response_body']);
                      $('#response').html(response['response']);
                      $('#post').html(response['post']);
                  }

              }, 500);  
          },

      });
  }