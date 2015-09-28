<?php
global $wpdb; 
$aID  =  $_REQUEST['user_id'];
$author_info = get_userdata($aID);

?>
 
  <script>
    function resetTabs(){
        jQuery("#myTabContent > div.tab-pane").removeClass('active in'); //Hide all content
        jQuery("ul.nav-tabs li").removeClass('active'); //Reset id's      
    }

    
    jQuery(document).ready(function(){
        jQuery("#tabs li").removeClass("active");
        jQuery("#tabs li:first").addClass("active"); // Activate first tab

        jQuery("#myTabContent > div.tab-pane").removeClass('active in'); // Initially hide all content
        jQuery("#myTabContent > div:first").addClass('active in'); // Show first tab content
        
        jQuery("ul.nav-tabs li").on("click",function(e) {
            e.preventDefault();
            if (jQuery(this).attr("class") == "active"){ //detection for current tab
             return       
            }
            else{             
              resetTabs();
              jQuery(this).attr("class","active"); // Activate this
              var select_a=jQuery(this).children('a').attr('name'); // Show content for current tab
              jQuery(select_a).addClass('active in');
            }
        });

        jQuery("#tab2").find('.panel-body').hide();
        jQuery("#tab2").children(".row").children(".col-md-10:first").find('.panel-body').show();
        jQuery(".panel-heading").on("click",function(){
          jQuery("#tab2").find('.panel-body').hide();
          jQuery(this).parent(".panel").find(".panel-body").show();
        });

       
      jQuery("#send_message").validate({
          rules: {
              "from": {
                  required: true,
                  email: true
              },  
              "your_name": {
                  required: true,
                  minlength: 3
              },
              "subject": {
                  required: true 
              },
              "contact_no": {
                  required: true,
                  number: true
              } ,
              "user_message": {
                  required: true,
                  minlength: 15,
                  maxlength: 1000
              }
          },
          //perform an AJAX post to ajax.php
          submitHandler: function() {
            var user_message = jQuery("#user_message").val();
            var subject = jQuery("#subject").val();
            var from = jQuery("#from").val();
            var name = jQuery("#name").val(); 
              jQuery.ajax({
                type: 'POST',
                url: '<?php echo home_url();?>/wp-admin/admin-ajax.php',
                data: {
                  action: 'send_message_user',
                  to:'<?php echo $author_info->user_email?>',
                  subject: subject,
                   from: from,
                    name: name,
                  user_message : user_message 
                },
                beforeSend : function (){
                  jQuery("#send_message_btn").val('In process. Please wait...');
                  jQuery("#send_message_btn").attr("disabled","disabled");
                },
                success: function(data, textStatus, XMLHttpRequest){
                  if(data=='0'){
                    alert('Message not sent. Please try again!!!');
                    jQuery("#send_message_btn").val('Send');
                    jQuery("#send_message_btn").removeAttr("disabled");

                  }
                  else{
                    alert('<?php echo $author_info->first_name.' '.$author_info->last_name;?> will contact you asap.');
                    jQuery("#send_message_btn").val('Send');
                    jQuery("#send_message_btn").removeAttr("disabled");
                    info_remove();
                  }
                },
                error: function(MLHttpRequest, textStatus, errorThrown){
                  alert(errorThrown);
                }
              });
          }
      });
        
     
    
  });
  </script>
   
 
   <ul class="nav nav-tabs">
      <li class="active"><a href="#" name="#tab1">General</a></li>
      <li><a href="#" name="#tab2">Occupation</a></li>
      <li ><a href="#" name="#tab3">Contact me</a></li>
    </ul>
  <div id="myTabContent" class="tab-content">
      <div  id="tab1" class="tab-pane fade active in mCustomScrollbar">
        <div class="row">
          <div class="col-md-3 user_basic_info"><img src="<?php echo get_noone_meta($aID,'thumbnail') ;?>" alt="<?php echo  $author_info->first_name.' '.$author_info->last_name ;?>" /><?php  //echo noone_gravatar_filter('',$aID,'thumbnail','',$author_info->first_name.' '.$author_info->last_name);?>
          <h3 class="text-primary"><?php echo $author_info->first_name.' '.$author_info->last_name;?></h3>
        <?php
          echo (trim($author_info->user_email)!='')? '<a href="mailto:'.$author_info->user_email.'">'.$author_info->user_email.'</a>':'';           
            ?></div>
          <div class="col-md-9">
            <?php if(trim(get_the_author_meta('description',$aID))!=''){?>
            <p><?php echo get_the_author_meta('description',$aID)?></p>  
            <?php }?>
             
          </div>
        </div>
         
        
      </div>
      <div  id="tab2" class="tab-pane fade mCustomScrollbar">
        <div class="row">
         
        <?php
        $self_title             = get_user_meta($aID, 'self_title', true);
        if($self_title!=''){
          $self_service           = get_user_meta($aID, 'self_service', true);
          $self_info              = nl2br(get_user_meta($aID, 'self_info', true));
          $self_address_line_1    = nl2br(get_user_meta($aID, 'self_address_line_1', true));
          $self_address_line_2    = nl2br(get_user_meta($aID, 'self_address_line_2', true));
          $self_city              = get_user_meta($aID, 'self_city', true);
          $self_state             = get_user_meta($aID, 'self_state', true);
          $self_country           = get_user_meta($aID, 'self_country', true);
        ?> <div class="col-md-10">
             <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Self Employee</h3>
              </div>
              <div class="panel-body">
                <?php echo add_comma_br($self_service);?> 
                <?php echo add_comma_br($self_info);?>
                <?php echo add_comma_br($self_address_line_1)?>
                <?php echo add_comma_br($self_address_line_2)?>
                <?php echo add_comma_br($self_city)?> <?php echo add_comma_br($self_state)?>
                <?php echo $self_country?>
              </div>
            </div> </div>
            <?php }
             
          $service_title          = get_user_meta($aID, 'service_title', true);
        if($service_title!=''){
          $service_type           = get_user_meta($aID, 'service_type', true);
          $service_post_name      = get_user_meta($aID, 'service_post_name', true);
          $service_info           = nl2br(get_user_meta($aID, 'service_info', true));
          $service_address_line_1 = nl2br(get_user_meta($aID, 'service_address_line_1', true));
          $service_address_line_2 = nl2br(get_user_meta($aID, 'service_address_line_2', true));
          $service_city           = get_user_meta($aID, 'service_city', true);
          $service_state          = get_user_meta($aID, 'service_state', true);
          $service_country        = get_user_meta($aID, 'service_country', true);
        ?>
        <div class="col-md-10">
        <div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title"><?php echo ($service_type=='govt')? 'Government': 'Private'; ?> Service</h3>
              </div>
              <div class="panel-body">
                <?php echo  add_comma_br($service_title);?> 
                <?php echo  add_comma_br($service_post_name);?> 
                <?php echo  add_comma_br($service_info);?> 
                <?php echo add_comma_br($service_address_line_1)?> 
                <?php echo add_comma_br($service_address_line_2)?> 
                <?php echo add_comma_br($service_city)?> <?php echo add_comma_br($service_state)?>
                <?php echo $service_country?>
              </div>
            </div>

          </div>
          <?php } 
          
           $retire_title           = get_user_meta($aID, 'retire_title', true);
        if($retire_title!=''){
          $retire_type            = get_user_meta($aID, 'retire_type', true);
          $retire_post_name       = get_user_meta($aID, 'retire_post_name', true);
          $retire_info            = nl2br(get_user_meta($aID, 'retire_info', true));
          $retire_address_line_1  = nl2br(get_user_meta($aID, 'retire_address_line_1', true));
          $retire_address_line_2  = nl2br(get_user_meta($aID, 'retire_address_line_2', true));
          $retire_city            = get_user_meta($aID, 'retire_city', true);
          $retire_state           = get_user_meta($aID, 'retire_state', true);
          $retire_country         = get_user_meta($aID, 'retire_country', true);
        ?><div class="col-md-10">
        <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Retired from <?php echo ($retire_type=='govt')?'Government': 'Private'; ?> Service</h3>
              </div>
              <div class="panel-body">
                <?php echo  add_comma_br($retire_title);?>
            <?php echo  add_comma_br($retire_post_name);?> 
            <?php echo  add_comma_br($retire_info);?>
            <?php echo add_comma_br($retire_address_line_1)?>
            <?php echo add_comma_br($retire_address_line_2)?>
            <?php echo add_comma_br($retire_city)?> <?php echo add_comma_br($retire_state)?>
            <?php echo $retire_country?>
              </div>
            </div>
 </div>
         
          <?php } 
          
           $social_title           = get_user_meta($aID, 'social_title', true);
          if($social_title!=''){
          $social_type            = get_user_meta($aID, 'social_type', true);
          $social_work_as         = get_user_meta($aID, 'social_work_as', true);
          $social_info            = nl2br(get_user_meta($aID, 'social_info', true));
          $social_address_line_1  = nl2br(get_user_meta($aID, 'social_address_line_1', true));
          $social_address_line_2  = nl2br(get_user_meta($aID, 'social_address_line_2', true));
          $social_city            = get_user_meta($aID, 'social_city', true);
          $social_state           = get_user_meta($aID, 'social_state', true);
          $social_country         = get_user_meta($aID, 'social_country', true);
        ?><div class="col-md-10">
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">Social Worker at <?php  echo ($social_type=='govt')?'Government': 'Private'; ?> place</h3>
          </div>
          <div class="panel-body">
            <?php echo  add_comma_br($social_title);?>
            <?php echo  add_comma_br($social_work_as);?>
            <?php echo  add_comma_br($social_info);?>
            <?php echo add_comma_br($social_address_line_1)?>
            <?php echo add_comma_br($social_address_line_2)?>
            <?php echo add_comma_br($social_city)?> <?php echo add_comma_br($social_state)?>
            <?php echo $social_country?>
          </div>
        </div></div>
          
          <?php }
          ?> 
     
        </div> 
       </div>
      <div  id="tab3" class="tab-pane fade mCustomScrollbar" >
        <div class="row">
          <div class="col-md-6">
            <div class="well bs-component">
              <form class="form-horizontal" id="send_message">
                <fieldset>
                  <legend>Send a message to me</legend>
                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Name</label>
                    <div class="col-lg-10">
                      <input type="text"  id="your_name" name="your_name" class="form-control"  >
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                      <input type="text" id="from" name="from" class="form-control"  >
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Subject</label>
                    <div class="col-lg-10">
                     <input type="text" id="subject" name="subject" class="form-control" >
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Contact No.</label>
                    <div class="col-lg-10">
                     <input type="text" id="contact_no" class="form-control" name="contact_no" >
                    </div>
                  </div>
                   
                  <div class="form-group">
                    <label for="textArea" class="col-lg-2 control-label">Textarea</label>
                    <div class="col-lg-10">
                      <textarea  id="user_message" class="form-control" name="user_message" ></textarea>
 
                      <!-- <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span> -->
                    </div>
                  </div>
                   
                   
                  <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                      
                      <button type="submit" id="send_message_btn" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </fieldset>
              </form>
            <div id="source-button" class="btn btn-primary btn-xs" style="display: none;">&lt; &gt;</div></div>

 
            
          </div>
          <div class="col-md-6">
               <div class="contact_address_block">   <h3 class="text-primary">Contact address:</h3> 
                <?php  echo add_comma_br(nl2br($author_info->address_line_1))?>
                <?php  echo add_comma_br(nl2br($author_info->address_line_2))?>
                <?php  echo add_comma_br($author_info->city)?> 
                <?php  echo $author_info->country?>
              </div>  
            </div>   
        </div>    
          
          
           
      </div>
      <?php
      if(trim($author_info->user_twitter)!='' ||trim($author_info->user_fb_id)!='' ||trim($author_info->linked_in)!='' ||trim($author_info->google_plus)!='' ){?>
  <hr>
  <div class="row"><div class="col-md-4">
    <h4 class="text-primary">Contact <?php echo $author_info->first_name.' '.$author_info->last_name;?>:</h4>
            </div>
            <div class="col-md-8 text-left"><?php echo'<span class="icons">';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->user_twitter.'" class="twitter" target="_blank"></a>';
            if($author_info->user_fb_id)
                echo'<a href="'.$author_info->user_fb_id.'" class="facebook" target="_blank"></a>';
            if($author_info->linked_in)
                echo'<a href="'.$author_info->linked_in.'" class="linkedin" target="_blank"></a>';
            if($author_info->google_plus)
                echo'<a href="'.$author_info->google_plus.'" class="googleplus" target="_blank"></a>';
            echo'</span>';?></div>
   </div>
   <?php }?>      
 </div>
 </div>
 
 
