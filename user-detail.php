<?php
global $wpdb; 
$aID  =  $_REQUEST['user_id'];
$author_info = get_userdata($aID);

?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Contact :: <?php echo $author_info->first_name.' '.$author_info->last_name;?></title>
  <script>
    function resetTabs(){
        jQuery("#user_content > div.tab-pane").hide(); //Hide all content
        jQuery("#tabs a").attr("id",""); //Reset id's      
    }

    var myUrl = window.location.href; //get URL
    var myUrlTab = myUrl.substring(myUrl.indexOf("#")); // For localhost/tabs.html#tab2, myUrlTab = #tab2     
    var myUrlTabName = myUrlTab.substring(0,4); // For the above example, myUrlTabName = #tab
    jQuery(document).ready(function(){
        jQuery("#user_content > div.tab-pane").hide(); // Initially hide all content
        jQuery("#tabs li:first a").attr("id","current"); // Activate first tab
        jQuery("#tab1").fadeIn(); // Show first tab content
        
        jQuery("#tabs a").on("click",function(e) {
            e.preventDefault();
            if (jQuery(this).attr("id") == "current"){ //detection for current tab
             return       
            }
            else{             
            resetTabs();
            jQuery(this).attr("id","current"); // Activate this
            jQuery(jQuery(this).attr('name')).fadeIn(); // Show content for current tab
            }
        });

        for (i = 1; i <= jQuery("#tabs li").length; i++) {
          if (myUrlTab == myUrlTabName + i) {
              resetTabs();
              jQuery("a[name='"+myUrlTab+"']").attr("id","current"); // Activate url tab
              jQuery(myUrlTab).fadeIn(); // Show url tab content        
          }
        }
 

       
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
   
 </head>
<body>

  <div class="container">

    <ul id="tabs" class="nav nav-tabs">
      <li ><a id="current" href="#" name="#tab1"><?php echo $author_info->first_name.' '.$author_info->last_name;?></a></li>
      <li><a id="" href="#" name="#tab2">Occupation</a></li>
      <li ><a id="" href="#" name="#tab3">Send Message</a></li>
      <li ><a href="javascript:void(0);" id="close-this"  onClick="info_remove()">X Close this</a></li>
    </ul>
 
  <div id="user_content" class="tab-content">
      <div  id="tab1" class="tab-pane active">
        <div class="row">
          <div class="col-md-3 user_basic_info"><?php  echo noone_gravatar_filter('',$aID,'thumbnail','',$author_info->first_name.' '.$author_info->last_name);?>
          <h3 class="text-primary"><?php echo $author_info->first_name.' '.$author_info->last_name;?></h3>
        <?php
          echo 'Email: <a href="mailto:'.$author_info->user_email.'">'.$author_info->user_email.'</a>';
           
            ?></div>
          <div class="col-md-9">
            <?php if(trim(get_the_author_meta('description',$aID))!=''){?>
            <div><?php echo get_the_author_meta('description',$aID)?></div>  
            <?php }?>
             
          </div>
        </div>
         
        
      </div>
      <div style="display: none;" id="tab2" class="tab-pane">
        <div class="row">
          <div class="col-md-3"><?php
        $self_title             = get_user_meta($aID, 'self_title', true);
        if($self_title!=''){
          $self_service           = get_user_meta($aID, 'self_service', true);
          $self_info              = get_user_meta($aID, 'self_info', true);
          $self_address_line_1    = get_user_meta($aID, 'self_address_line_1', true);
          $self_address_line_2    = get_user_meta($aID, 'self_address_line_2', true);
          $self_city              = get_user_meta($aID, 'self_city', true);
          $self_state             = get_user_meta($aID, 'self_state', true);
          $self_country           = get_user_meta($aID, 'self_country', true);
        ?>
         
          <div class="text-left profession_info">   <h3 class="text-primary">Self Employee:</h3> 
            <?php echo add_comma_br($self_service);?> 
            <?php echo add_comma_br($self_info);?>
            <?php echo add_comma_br($self_address_line_1)?>
            <?php echo add_comma_br($self_address_line_2)?>
            <?php echo add_comma($self_city)?> <?php echo add_comma_br($self_state)?>
            <?php echo $self_country?>
          </div>
          <?php }
          ?></div>
          <div class="col-md-3"><?php
          $service_title          = get_user_meta($aID, 'service_title', true);
        if($service_title!=''){
          $service_type           = get_user_meta($aID, 'service_type', true);
          $service_post_name      = get_user_meta($aID, 'service_post_name', true);
          $service_info           = get_user_meta($aID, 'service_info', true);
          $service_address_line_1 = get_user_meta($aID, 'service_address_line_1', true);
          $service_address_line_2 = get_user_meta($aID, 'service_address_line_2', true);
          $service_city           = get_user_meta($aID, 'service_city', true);
          $service_state          = get_user_meta($aID, 'service_state', true);
          $service_country        = get_user_meta($aID, 'service_country', true);
        ?>
          <div class="text-left profession_info"><h3 class="text-primary"><?php echo ($service_type=='govt')? 'Government': 'Private'; ?> Service</h3> 
            <?php echo  add_comma_br($service_title);?> 
            <?php echo  add_comma_br($service_post_name);?> 
            <?php echo  add_comma_br($service_info);?> 
            <?php echo add_comma_br($service_address_line_1)?> 
            <?php echo add_comma_br($service_address_line_2)?> 
            <?php echo add_comma($service_city)?> <?php echo add_comma_br($service_state)?>
            <?php echo $service_country?>
          </div>
          <?php }?></div>
          <div class="col-md-3"><?php $retire_title           = get_user_meta($aID, 'retire_title', true);
        if($retire_title!=''){
          $retire_type            = get_user_meta($aID, 'retire_type', true);
          $retire_post_name       = get_user_meta($aID, 'retire_post_name', true);
          $retire_info            = get_user_meta($aID, 'retire_info', true);
          $retire_address_line_1  = get_user_meta($aID, 'retire_address_line_1', true);
          $retire_address_line_2  = get_user_meta($aID, 'retire_address_line_2', true);
          $retire_city            = get_user_meta($aID, 'retire_city', true);
          $retire_state           = get_user_meta($aID, 'retire_state', true);
          $retire_country         = get_user_meta($aID, 'retire_country', true);
        ?>
          <div class="text-left profession_info">   <h3 class="text-primary">Retired from <?php echo ($retire_type=='govt')?'Government': 'Private'; ?> Service</h3> 
            <?php echo  add_comma_br($retire_title);?>
            <?php echo  add_comma_br($retire_post_name);?> 
            <?php echo  add_comma_br($retire_info);?>
            <?php echo add_comma_br($retire_address_line_1)?>
            <?php echo add_comma_br($retire_address_line_2)?>
            <?php echo add_comma($retire_city)?> <?php echo add_comma_br($retire_state)?>
            <?php echo $retire_country?>
          </div>
          <?php } ?></div>
          <div class="col-md-3"><?php $social_title           = get_user_meta($aID, 'social_title', true);
          if($social_title!=''){
          $social_type            = get_user_meta($aID, 'social_type', true);
          $social_work_as         = get_user_meta($aID, 'social_work_as', true);
          $social_info            = get_user_meta($aID, 'social_info', true);
          $social_address_line_1  = get_user_meta($aID, 'social_address_line_1', true);
          $social_address_line_2  = get_user_meta($aID, 'social_address_line_2', true);
          $social_city            = get_user_meta($aID, 'social_city', true);
          $social_state           = get_user_meta($aID, 'social_state', true);
          $social_country         = get_user_meta($aID, 'social_country', true);
        ?>
          <div class="text-left profession_info">   <h3 class="text-primary">Social Worker at <?php  echo ($social_type=='govt')?'Government': 'Private'; ?> place</h3> 
            <?php echo  add_comma_br($social_title);?>
            <?php echo  add_comma_br($social_work_as);?>
            <?php echo  add_comma_br($social_info);?>
            <?php echo add_comma_br($social_address_line_1)?>
            <?php echo add_comma_br($social_address_line_2)?>
            <?php echo add_comma($social_city)?> <?php echo add_comma_br($social_state)?>
            <?php echo $social_country?>
          </div>
          <?php }
          ?></div>
           
        </div> 
       </div>
      <div style="display: none;" id="tab3" class="tab-pane">
        <div class="row">
          <div class="col-md-6">
            <h3 class="text-primary">Send Message</h3>
           <form id="send_message">
          <dl> 
            <dt> <input type="text" placeholder="your email address" id="from" name="from" class="form-control"  ></dt> 
            <dt> <input type="text" placeholder="your name" id="your_name" name="your_name" class="form-control"  ></dt> 
             <dt> <input type="text" placeholder="subject" id="subject" name="subject" class="form-control" ></dt>
             <dt> <input type="text" placeholder="your contact number"  id="contact_no" class="form-control" name="contact_no" ></dt>
            <dt><textarea placeholder="your message" id="user_message" class="form-control" name="user_message" ></textarea> </dt> 
            <dt>&nbsp;</dt><dd><input type="submit" id="send_message_btn" class="btn btn-info btn-block" value="Send"></dd></dl>
           </form>
          </div>
          <div class="col-md-6">
               <div class="contact_address_block">   <h3 class="text-primary">Contact Address:</h3> 
                <?php  echo add_comma_br($author_info->address_line_1)?>
                <?php  echo add_comma_br($author_info->address_line_2)?>
                <?php  echo add_comma($author_info->city)?> 
                <?php  echo $author_info->country?>
              </div>  
            </div>   
        </div>    
          
          
           
      </div>
  <hr>
  <div class="row"><div class="col-md-4">

    <h4 class="text-primary">Contact <?php echo $author_info->first_name.' '.$author_info->last_name;?>:</h4>
            </div>
            <div class="col-md-8 text-left"><?php echo'<span class="icons">';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->user_twitter.'" class="twitter" target="_blank">&nbsp;</a>';
            if($author_info->user_fb_id)
                echo'<a href="'.$author_info->user_fb_id.'" class="facebook" target="_blank">&nbsp;</a>';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->linked_in.'" class="linkedin" target="_blank">&nbsp;</a>';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->google_plus.'" class="googleplus" target="_blank">&nbsp;</a>';
            echo'</span>';?></div>
   </div>      
 </div>
 </div>
</body></html>