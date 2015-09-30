<?php
/*
Plugin Name: No-One Plugin
Description: Listing + Details
**/

register_activation_hook(__FILE__, 'noone_install');
register_deactivation_hook(__FILE__, 'noone_deactivation');

function noone_deactivation(){
	global $wpdb;
	$option_name = 'noone_active';
	delete_option( $option_name );	
}
function noone_install() {
	
	global $wpdb;
	add_option( 'noone_active', '1', '', 'yes' );
	
    $post_if = $wpdb->get_var("SELECT count(post_name) FROM $wpdb->posts WHERE post_name like 'user-search' AND post_type='page'");
    if($post_if < 1){
        $my_page = array(
        'post_title' => 'User Search',
        'post_content' => 'No need to add any shortcode or select any template. Page will get required template itself,',
        'post_status' => 'publish',
        'post_type' => 'page' 
        );

        $post_id = wp_insert_post($my_page);
    }
    
    $post_if = $wpdb->get_var("SELECT count(post_name) FROM $wpdb->posts WHERE post_name like 'user-map-search' AND post_type='page'");
    if($post_if < 1){
        $my_page = array(
        'post_title' => 'User Map Search',
        'post_content' => 'No need to add any shortcode or select any template. Page will get required template itself,',
        'post_status' => 'publish',
        'post_type' => 'page' 
        );

        $post_id = wp_insert_post($my_page);
    }
}   

//Template fallback
add_action("template_redirect", 'nonone_theme_redirect');

function nonone_theme_redirect() {
	
	if(!get_option('noone_active'))
		return false;
		
    global $wp;
    $plugindir = dirname( __FILE__ );
	if ($wp->query_vars["pagename"] == 'user-search') {
		$templatefilename = 'page-user-search.php';
		$return_template = plugin_dir_path( __FILE__ ) . '/template/' . $templatefilename;
		do_theme_redirect($return_template);
	}
	
	if ($wp->query_vars["pagename"] == 'user-map-search') {
		$templatefilename = 'page-user-map-search.php';
		$return_template = plugin_dir_path( __FILE__ ) . '/template/' . $templatefilename;
		do_theme_redirect($return_template);
	}
}

function do_theme_redirect($url) {
	
	if(!get_option('noone_active'))
		return false;
		
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
} 
add_action('init','add_noone_image_size');

function add_noone_image_size(){
    add_image_size('gomap_marker',20,20);
    add_image_size('gomap_marker_html',100,85);
    
}

function noone_users($user)
{
	if(!get_option('noone_active'))
		return false;
	
    $userid                 = $user->ID;
    $address_line_1         = get_user_meta($userid, 'address_line_1', true);
   // $address_line_2         = get_user_meta($userid, 'address_line_2', true);
    $city                   = get_user_meta($userid, 'city', true);
    $district                   = get_user_meta($userid, 'district', true);
    $state                  = get_user_meta($userid, 'state', true);
    $pin_code                   = get_user_meta($userid, 'pin_code', true);
    $country                = get_user_meta($userid, 'country', true);

    $user_twitter           = get_user_meta($userid, 'user_twitter', true);
    $user_fb_id             = get_user_meta($userid, 'user_fb_id', true);
    $google_plus            = get_user_meta($userid, 'google_plus', true);
    $linked_in              = get_user_meta($userid, 'linked_in', true);

    $perma_lat              = get_user_meta($userid, 'perma_lat', true);
    $perma_long             = get_user_meta($userid, 'perma_long', true);
    $self_title             = get_user_meta($userid, 'self_title', true);
    $self_service           = get_user_meta($userid, 'self_service', true);
    $self_info              = get_user_meta($userid, 'self_info', true);
    $self_address_line_1    = get_user_meta($userid, 'self_address_line_1', true);
    //$self_address_line_2    = get_user_meta($userid, 'self_address_line_2', true);
    $self_district                   = get_user_meta($userid, 'self_district', true);
    $self_pin_code                   = get_user_meta($userid, 'self_pin_code', true);
    $self_city              = get_user_meta($userid, 'self_city', true);
    $self_state             = get_user_meta($userid, 'self_state', true);
    $self_country           = get_user_meta($userid, 'self_country', true);
    $service_type           = get_user_meta($userid, 'service_type', true);
    $service_title          = get_user_meta($userid, 'service_title', true);
    $service_post_name      = get_user_meta($userid, 'service_post_name', true);
    $service_info           = get_user_meta($userid, 'service_info', true);
    $service_address_line_1 = get_user_meta($userid, 'service_address_line_1', true);
   // $service_address_line_2 = get_user_meta($userid, 'service_address_line_2', true);
    $service_district                   = get_user_meta($userid, 'service_district', true);
    $service_pin_code                   = get_user_meta($userid, 'service_pin_code', true);
    $service_city           = get_user_meta($userid, 'service_city', true);
    $service_state          = get_user_meta($userid, 'service_state', true);
    $service_country        = get_user_meta($userid, 'service_country', true);
    $retire_type            = get_user_meta($userid, 'retire_type', true);
    $retire_title           = get_user_meta($userid, 'retire_title', true);
    $retire_post_name       = get_user_meta($userid, 'retire_post_name', true);
    $retire_info            = get_user_meta($userid, 'retire_info', true);
    $retire_address_line_1  = get_user_meta($userid, 'retire_address_line_1', true);
    //$retire_address_line_2  = get_user_meta($userid, 'retire_address_line_2', true);
    $retire_district                   = get_user_meta($userid, 'retire_district', true);
    $retire_pin_code                   = get_user_meta($userid, 'retire_pin_code', true);
    $retire_city            = get_user_meta($userid, 'retire_city', true);
    $retire_state           = get_user_meta($userid, 'retire_state', true);
    $retire_country         = get_user_meta($userid, 'retire_country', true);
    $social_type            = get_user_meta($userid, 'social_type', true);
    $social_title           = get_user_meta($userid, 'social_title', true);
    $social_work_as         = get_user_meta($userid, 'social_work_as', true);
    $social_info            = get_user_meta($userid, 'social_info', true);
    $social_address_line_1  = get_user_meta($userid, 'social_address_line_1', true);
    //$social_address_line_2  = get_user_meta($userid, 'social_address_line_2', true);
    $social_district                   = get_user_meta($userid, 'social_district', true);
    $social_pin_code                   = get_user_meta($userid, 'social_pin_code', true);
    $social_city            = get_user_meta($userid, 'social_city', true);
    $social_state           = get_user_meta($userid, 'social_state', true);
    $social_country         = get_user_meta($userid, 'social_country', true);
    if(!$perma_lat)
        $perma_lat  =   '26.847767';
    if(!$perma_long)
        $perma_long =   '75.769539';
    // vars
    $noone_url = get_the_author_meta( 'noone_meta', $userid );
    $noone_upload_url = get_the_author_meta( 'noone_upload_meta', $userid );
    $noone_upload_edit_url = get_the_author_meta( 'noone_upload_edit_meta', $userid );

    if(!$noone_upload_url){
        $btn_text = 'Upload New Image';
    } else {
        $noone_upload_edit_url = get_home_url().get_the_author_meta( 'noone_upload_edit_meta', $userid );
        $btn_text = 'Change Current Image';
    }
?>
 
   <div class="wrap">

        <div class="icon32" id="icon-users"><br></div><h2><u>Permanent Address</u></h2>
        <table class="form-table">
            <tbody>
                        <tr><td colspan="2">
                    <?php
    wp_register_script('noone_google_map', 'http://maps.google.com/maps/api/js?sensor=false');
    wp_enqueue_script('noone_google_map');
    wp_register_script('noone_gomap', plugins_url('assets/js/jquery.gomap-1.3.3.js', __FILE__));
    wp_enqueue_script('noone_gomap');
    wp_enqueue_script('jquery');
    if (trim($city) != '' && trim($state) != '')
    {
        $uaddress = $address_line_1 . "," . $address_line_2 . "," . $city . "," . $state;
        $uaddress = str_replace(" ", "+", $uaddress);
    } //trim($city) != '' && trim($state) != ''
?>
                                <div id="mapnew" style="width:100%; height:300px; clear:both;"></div> 
                                 <input type="hidden" class="perma_lat" name="perma_lat" value="<?php
    echo $perma_lat;
?>" />
                                 <input type="hidden" class="perma_long" name="perma_long" value="<?php
    echo $perma_long;
?>" />
                <script type="text/javascript">
                    function getval(id,value) { 

                        jQuery('#'+id).parents(".key").siblings('.district').val(value)
                        //jQuery('.district').val(value);
                        jQuery('.key').html('');
                    } 
                    function sgetval(id,value) { 
                       jQuery('#'+id).parents(".skey").siblings('.state').val(value)
                        jQuery('.skey').html('');
                    }
                    jQuery(document).ready(function(){
                        jQuery( ".district" ).keyup(function() {
                            var this_div=jQuery(this);
                            var did=jQuery(this).val();
                            var ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"; 
                            var data ={ action: "district_action",  district:did    };
                            jQuery.post(ajaxurl, data, function (response){
                                this_div.siblings(".key").html(response);
                            });
                        });
                        jQuery( ".state" ).keyup(function() {
                            var this_div=jQuery(this);
                            var sid=jQuery(this).val();
                            var ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"; 
                            var data ={ action: "state_action",  state:sid    };
                            jQuery.post(ajaxurl, data, function (response){
                                this_div.siblings(".skey").html(response);
                            });
                        });
                    });
                

                jQuery(function() {
                    
                    
                    jQuery("#mapnew").goMap({
                        markers: [ { 
                   "latitude":"<?php echo $perma_lat;?>",
                   "longitude":"<?php echo $perma_long;?>", 
                    title: 'marker title 1' ,
                    id: 'admin_marker', 
                    draggable: true,
                     html: { 
                        content: 'Your Location.<br/>', 
                        popup:true 
                    } 
                }]});
                
                
                 jQuery.goMap.createListener({type:'marker', marker:'admin_marker'}, 'dragend', function() { 
						var arr    =   jQuery.goMap.getMarkers(); 
						 var res    =   arr[0].split(",");
						 jQuery(".perma_lat").val(res[0]);
						 jQuery(".perma_long").val(res[1]);
						});
                    });
                </script><br/> (Drag marker to set your location.)
                    </td></tr>
                <tr>
                    <th><label for="address_line_1">Address</label></th>
                    <td><textarea class="regular-text" id="address_line_1" name="address_line_1"><?php echo $address_line_1;?></textarea>
                     <span class="description">Flat No./Building Name/Plot No./Street Name</span></td>
                </tr>
				<tr>
                    <th><label for="city">City</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $city;
?>" id="city" name="city"> <span class="description">City Name</span></td>
                </tr>

                <tr>
                    <th><label for="district">District</label></th>
                    <td><input type="text" class="regular-text district" autocomplete="off"  value="<?php
    echo $district;
?>" id="district" name="district"> <div class="key"></div>
<span class="description">District Name</span></td>
                </tr>

                <tr>
                    <th><label for="state">State</label></th>
                    <td><input type="text" class="regular-text state" autocomplete="off"  value="<?php
    echo $state;
?>" id="state" name="state"> <div class="skey"></div> <span class="description">State Name</span></td>
                </tr>
                 <tr>
                    <th><label for="pin_code">Pin Code</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $pin_code;
?>" id="pin_code" name="pin_code"> <span class="description">Pin Code</span></td>
                </tr>

                <tr>
                    <th><label for="country">Country</label></th>
                    <td><?php
    country_drop_down('country', $country);
?>  <span class="description">Country Name</span></td>
                </tr>
            </tbody>
        </table>
        <div class="icon32" id="icon-users"><br></div><h2><u>Profile Image</u></h2>

    <div id="noone_container">
    <h3><?php _e( 'Custom User Profile Photo', 'custom-user-profile-photo' ); ?></h3>
 
    <table class="form-table">
 
        <tr>
            <th><label for="noone_meta"><?php _e( 'Profile Photo', 'custom-user-profile-photo' ); ?></label></th>
            <td>
                <!-- Outputs the image after save -->
                <div id="current_img">
                    <?php if($noone_upload_url): ?>
                        <img src="<?php echo esc_url( $noone_upload_url ); ?>" class="noone-current-img">
                        <div class="edit_options uploaded">
                            <a class="remove_img"><span>Remove</span></a>
                            <a href="<?php echo $noone_upload_edit_url; ?>" class="edit_img" target="_blank"><span>Edit</span></a>
                        </div>
                    <?php elseif($noone_url) : ?>
                        <img src="<?php echo esc_url( $noone_url ); ?>" class="noone-current-img">
                        <div class="edit_options single">
                            <a class="remove_img"><span>Remove</span></a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Select an option: Upload to WPMU or External URL -->
                <div id="noone_options">
                    <input type="radio" id="upload_option" name="img_option" value="upload" class="tog" checked> 
                    <label for="upload_option">Upload New Image</label><br>
                    <input type="radio" id="external_option" name="img_option" value="external" class="tog">
                    <label for="external_option">Use External URL</label><br>
                </div>

                <!-- Hold the value here if this is a WPMU image -->
                <div id="noone_upload">
                    <input type="hidden" name="noone_upload_meta" id="noone_upload_meta" value="<?php echo esc_url_raw( $noone_upload_url ); ?>" class="hidden" />
                    <input type="hidden" name="noone_upload_edit_meta" id="noone_upload_edit_meta" value="<?php echo esc_url_raw( $noone_upload_edit_url ); ?>" class="hidden" />
                    <input type='button' class="noone_wpmu_button button-primary" value="<?php _e( $btn_text, 'custom-user-profile-photo' ); ?>" id="uploadimage"/><br />
                </div>  
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <div id="noone_external">
                    <input type="text" name="noone_meta" id="noone_meta" value="<?php echo esc_url_raw( $noone_url ); ?>" class="regular-text" />
                </div>
                <!-- Outputs the save button -->
                <span class="description"><?php _e( 'Upload a custom photo for your user profile or use a URL to a pre-existing photo.', 'custom-user-profile-photo' ); ?></span>
                <p class="description"><?php _e('Update Profile to save your changes.', 'custom-user-profile-photo'); ?></p>
            </td>
        </tr>
 
    </table><!-- end form-table -->
</div> <!-- end #noone_container -->

    <?php wp_enqueue_media(); // Enqueue the WordPress MEdia Uploader ?>

        <div class="icon32" id="icon-users"><br></div><h2><u>Social Communication</u></h2>
        <table class="form-table">
            <tbody>
                      <tr>
                    <th><label for="user_twitter">Twitter</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $user_twitter;
?>" id="user_twitter" name="user_twitter"> <span class="description">http://twitter.com/avinash</span></td>
                </tr>

                <tr>
                    <th><label for="user_fb_id">Facebook ID</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $user_fb_id;
?>" id="user_fb_id" name="user_fb_id"> <span class="description">https://facebook.com/avinash</span></td>
                </tr>
                <tr>
                    <th><label for="user_fb_id">Google page URL</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $google_plus;
?>" id="google_plus" name="google_plus"> <span class="description">http://plus.google.com/avinash</span></td>
                </tr>
                <tr>
                    <th><label for="user_fb_id">Linkedin page URL</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $linked_in;
?>" id="linked_in" name="linked_in"> <span class="description">http://linkedin.com/in/avinash</span></td>
                </tr>
            </tbody>
        </table>

        <div class="icon32" id="icon-tools"><br></div><h2><u>Occupation</u></h2>
        <?php
    wp_register_script('noone_jquery_ui', plugins_url('assets/js/jquery-ui.js', __FILE__));
    wp_enqueue_script('noone_jquery_ui');
    wp_register_style('noone_jquery_css', plugins_url('assets/css/jquery-ui.css', __FILE__));
    wp_enqueue_style('noone_jquery_css');
?>
       <script>
        jQuery(function() {
            jQuery( "#tabs" ).tabs();
        });
        </script>
        <div id="tabs">
          <ul>
            <li><a href="#tabs-1">Entrepreneur</a></li>
            <li><a href="#tabs-2">In Service</a></li>
            <li><a href="#tabs-3">Retired from Service</a></li>
            <li><a href="#tabs-4">Social Worker</a></li>
        </ul>
        <div id="tabs-1">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="self_title">Title</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $self_title;
?>" id="self_title" name="self_title"> <span class="description">Company/Shop Name</span></td>
                    </tr>
                    <tr>
                        <th><label for="self_service">Company Job</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $self_service;
?>" id="self_service" name="self_service"> <span class="description">Manufacturing/Service Provider/Food Processing Unit</span></td>
                    </tr>
                    <tr>
                        <th><label for="self_info">Company Info</label></th>
                        <td><textarea class="regular-text"  id="self_info" name="self_info"><?php
    echo $self_info;
?></textarea> <span class="description">About your Company,like: Product, Environment, Requirements etc.</span></td>
                    </tr>


                    <tr>
                        <th><label for="self_address_line_1">Address Line-1</label></th>
                        <td><textarea  class="regular-text" id="self_address_line_1" name="self_address_line_1"><?php echo $self_address_line_1;?></textarea> <span class="description">Flat No./Building Name/Plot No./Street Name</span></td>
                    </tr>

                    <!-- <tr>
                        <th><label for="self_address_line_2">Address Line-2</label></th>
                        <td><input type="text" class="regular-text"  value="<?php // echo $self_address_line_2;?>" id="self_address_line_2" name="self_address_line_2"> <span class="description">Area Name/Land Mark</span></td>
                    </tr> -->

                    <tr>
                        <th><label for="self_city">City</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $self_city;
?>" id="self_city" name="self_city"> <span class="description">City Name</span></td>
                    </tr>

                    <tr>
                    <th><label for="self_district">District</label></th>
                    <td><input type="text" class="regular-text district" autocomplete="off"  value="<?php
    echo $self_district;
?>" id="self_district" name="self_district"> <div class="key"></div>
<span class="description">District Name</span></td>
                </tr>
   
                <tr>
                    <th><label for="self_state">State</label></th>
                    <td><input type="text" class="regular-text state" autocomplete="off"  value="<?php
    echo $self_state;
?>" id="self_state" name="self_state"> <div class="skey"></div> <span class="description">State Name</span></td>
                </tr>
                 <tr>
                    <th><label for="self_pin_code">Pin Code</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $self_pin_code;
?>" id="self_pin_code" name="self_pin_code"> <span class="description">Pin Code</span></td>
                </tr>


                  

                    <tr>
                        <th><label for="self_country">Country</label></th>
                        <td><?php
    country_drop_down('self_country', $self_country);
?>  <span class="description">Country Name</span></td>
                    </tr>

                </tbody>    
            </table>  
        </div>
        <div id="tabs-2">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="service_type">&nbsp;</label></th>
                        <td><input type="radio" name="service_type" value="govt" <?php
    checked($service_type, 'govt');
?>> Government Sector<br>
                            <input type="radio" name="service_type" value="private" <?php
    checked($service_type, 'private');
?>> Private Sector<br>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="service_title">Department Name</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $service_title;
?>" id="service_title" name="service_title"> <span class="description">Incom Tax/Sales Tax/JDA/IT  etc.</span></td>
                    </tr>
                    <tr>
                        <th><label for="service_post_name">Post Name</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $service_post_name;
?>" id="service_post_name" name="service_post_name"> <span class="description">Accountant/Manager/Team Leader/Superwiser etc.</span></td>
                    </tr>
                    <tr>
                        <th><label for="service_info">Service Info</label></th>
                        <td><textarea class="regular-text"  id="service_info" name="service_info"><?php
    echo $service_info;
?></textarea> <span class="description">About your Service,like: your work, your area etc.</span></td>
                    </tr>

                    <tr>
                        <th><label for="service_address_line_1">Address Line-1</label></th>
                        <td><textarea class="regular-text" id="service_address_line_1" name="service_address_line_1"><?php echo $service_address_line_1;?></textarea> <span class="description">Flat No./Building Name/Plot No./Street Name</span></td>
                    </tr>
<!-- 
                    <tr>
                        <th><label for="service_address_line_2">Address Line-2</label></th>
                        <td><input type="text" class="regular-text"  value="<?php // echo $service_address_line_2;?>" id="service_address_line_2" name="service_address_line_2"> <span class="description">Area Name/Land Mark</span></td>
                    </tr> -->

                    <tr>
                        <th><label for="service_city">City</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $service_city;
?>" id="service_city" name="service_city"> <span class="description">City Name</span></td>
                    </tr>

 <tr>
                    <th><label for="service_district">District</label></th>
                    <td><input type="text" class="regular-text district" autocomplete="off"  value="<?php
    echo $service_district;
?>" id="service_district" name="service_district"> <div class="key"></div>
<span class="description">District Name</span></td>
                </tr>
   
                <tr>
                    <th><label for="service_state">State</label></th>
                    <td><input type="text" class="regular-text state" autocomplete="off"  value="<?php
    echo $service_state;
?>" id="service_state" name="service_state"> <div class="skey"></div> <span class="description">State Name</span></td>
                </tr>
                 <tr>
                    <th><label for="service_pin_code">Pin Code</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $service_pin_code;
?>" id="service_pin_code" name="service_pin_code"> <span class="description">Pin Code</span></td>
                </tr>

                    

                    <tr>
                        <th><label for="service_country">Country</label></th>
                        <td><?php
    country_drop_down('service_country', $service_country);
?>  <span class="description">Country Name</span></td>
                    </tr>

                </tbody>    
            </table>    
        </div>
        <div id="tabs-3">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="retire_type">&nbsp;</label></th>
                        <td><input type="radio" name="retire_type" value="govt" <?php
    checked($retire_type, 'govt');
?> > Government Sector<br>
                            <input type="radio" name="retire_type" value="private" <?php
    checked($retire_type, 'private');
?> > Private Sector<br>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="retire_title">Department Name</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $retire_title;
?>" id="retire_title" name="retire_title"> <span class="description">Incom Tax/Sales Tax/JDA/IT  etc.</span></td>
                    </tr>
                    <tr>
                        <th><label for="retire_post_name">Post Name</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $retire_type;
?>" id="retire_post_name" name="retire_post_name"> <span class="description">Accountant/Manager/Team Leader/Superwiser etc.</span></td>
                    </tr>
                    <tr>
                        <th><label for="retire_info">Service Info</label></th>
                        <td><textarea class="regular-text"  id="retire_info" name="retire_info"><?php
    echo $retire_info;
?></textarea> <span class="description">About your Service,like: your work, your area etc.</span></td>
                    </tr>

                    <tr>
                        <th><label for="retire_address_line_1">Address Line-1</label></th>
                        <td><textarea class="regular-text" id="retire_address_line_1" name="retire_address_line_1"><?php echo $retire_address_line_1;?></textarea> <span class="description">Flat No./Building Name/Plot No./Street Name</span></td>
                    </tr>

                   <!--  <tr>
                        <th><label for="retire_address_line_2">Address Line-2</label></th>
                        <td><input type="text" class="regular-text"  value="<?php//echo $retire_address_line_2;?>" id="retire_address_line_2" name="retire_address_line_2"> <span class="description">Area Name/Land Mark</span></td>
                    </tr>
 -->
                    <tr>
                        <th><label for="retire_city">City</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $retire_city;
?>" id="retire_city" name="retire_city"> <span class="description">City Name</span></td>
                    </tr>

   

 <tr>
                    <th><label for="retire_district">District</label></th>
                    <td><input type="text" class="regular-text district" autocomplete="off"  value="<?php
    echo $retire_district;
?>" id="retire_district" name="retire_district"> <div class="key"></div>
<span class="description">District Name</span></td>
                </tr>
   
                <tr>
                    <th><label for="retire_state">State</label></th>
                    <td><input type="text" class="regular-text state" autocomplete="off"  value="<?php
    echo $retire_state;
?>" id="retire_state" name="retire_state"> <div class="skey"></div> <span class="description">State Name</span></td>
                </tr>
                 <tr>
                    <th><label for="retire_pin_code">Pin Code</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $retire_pin_code;
?>" id="retire_pin_code" name="retire_pin_code"> <span class="description">Pin Code</span></td>
                </tr>

                    
                    <tr>
                        <th><label for="retire_country">Country</label></th>
                        <td><?php
    country_drop_down('retire_country', $retire_country);
?>  <span class="description">Country Name</span></td>
                    </tr>

                </tbody>    
            </table>
        </div>
        <div id="tabs-4">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="social_type">&nbsp;</label></th>
                        <td><input type="radio" name="social_type" value="govt" <?php
    checked($social_type, 'govt');
?> > Government Sector<br>
                            <input type="radio" name="social_type" value="private" <?php
    checked($social_type, 'private');
?> > Private Sector<br>
                        </td>
                    </tr>

                    <tr>
                        <th><label for="social_title">Department/Society Name</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $social_title;
?>" id="social_title" name="social_title"> <span class="description">Incom Tax/Sales Tax/JDA/IT  etc.</span></td>
                    </tr>
                    <tr>
                        <th><label for="social_work_as">Working as</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $social_work_as;
?>" id="social_work_as" name="social_work_as"> <span class="description">Accountant/Manager/Team Leader/Superwiser etc.</span></td>
                    </tr>
                    <tr>
                        <th><label for="social_info">Service Info</label></th>
                        <td><textarea class="regular-text"  id="social_info" name="social_info"><?php
    echo $social_info;
?></textarea> <span class="description">About your Service,like: your work, your area etc.</span></td>
                    </tr>

                    <tr>
                        <th><label for="social_address_line_1">Address Line-1</label></th>
                        <td><textarea   class="regular-text" id="social_address_line_1" name="social_address_line_1"><?php echo $social_address_line_1;
?></textarea> <span class="description">Flat No./Building Name/Plot No./Street Name</span></td>
                    </tr>

                    <!-- <tr>
                        <th><label for="social_address_line_2">Address Line-2</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    //echo $social_address_line_2;?>" id="social_address_line_2" name="social_address_line_2"> <span class="description">Area Name/Land Mark</span></td>
                    </tr> -->

                    <tr>
                        <th><label for="social_city">City</label></th>
                        <td><input type="text" class="regular-text"  value="<?php
    echo $social_city;
?>" id="social_city" name="social_city"> <span class="description">City Name</span></td>
                    </tr>

 <tr>
                    <th><label for="social_district">District</label></th>
                    <td><input type="text" class="regular-text district" autocomplete="off"  value="<?php
    echo $social_district;
?>" id="social_district" name="social_district"> <div class="key"></div>
<span class="description">District Name</span></td>
                </tr>
   
                <tr>
                    <th><label for="social_state">State</label></th>
                    <td><input type="text" class="regular-text state" autocomplete="off"  value="<?php
    echo $social_state;
?>" id="social_state" name="social_state"> <div class="skey"></div> <span class="description">State Name</span></td>
                </tr>
                 <tr>
                    <th><label for="social_pin_code">Pin Code</label></th>
                    <td><input type="text" class="regular-text"  value="<?php
    echo $social_pin_code;
?>" id="social_pin_code" name="social_pin_code"> <span class="description">Pin Code</span></td>
                </tr> 

                    <tr>
                        <th><label for="social_country">Country</label></th>
                        <td><?php
    country_drop_down('social_country', $social_country);
?>  <span class="description">Country Name</span></td>
                    </tr>

                </tbody>    
            </table>    
        </div>
    </div>



</div>
<?php
}
function noone_users_save($userid)
{
    //print_r($_POST);die;
    update_user_meta($userid, 'perma_lat', $_POST['perma_lat']);
    update_user_meta($userid, 'perma_long', $_POST['perma_long']);
    update_user_meta($userid, 'address_line_1', $_POST['address_line_1']);
    //update_user_meta($userid, 'address_line_2', $_POST['address_line_2']);
    update_user_meta($userid, 'city', $_POST['city']);
    update_user_meta($userid, 'district', $_POST['district']);
    update_user_meta($userid, 'pin_code', $_POST['pin_code']);
    update_user_meta($userid, 'state', $_POST['state']);
    update_user_meta($userid, 'country', $_POST['country']);

    update_user_meta($userid, 'user_twitter', $_POST['user_twitter']);
    update_user_meta($userid, 'user_fb_id', $_POST['user_fb_id']);
    update_user_meta($userid, 'linked_in', $_POST['linked_in']);
    update_user_meta($userid, 'google_plus', $_POST['google_plus']);

    update_user_meta($userid, 'self_title', $_POST['self_title']);
    update_user_meta($userid, 'self_service', $_POST['self_service']);
    update_user_meta($userid, 'self_info', $_POST['self_info']);
    update_user_meta($userid, 'self_address_line_1', $_POST['self_address_line_1']);
    //update_user_meta($userid, 'self_address_line_2', $_POST['self_address_line_2']);
    update_user_meta($userid, 'self_district', $_POST['self_district']);
    update_user_meta($userid, 'self_pin_code', $_POST['self_pin_code']);
    update_user_meta($userid, 'self_city', $_POST['self_city']);
    update_user_meta($userid, 'self_state', $_POST['self_state']);
    update_user_meta($userid, 'self_country', $_POST['self_country']);
    update_user_meta($userid, 'service_type', $_POST['service_type']);
    update_user_meta($userid, 'service_title', $_POST['service_title']);
    update_user_meta($userid, 'service_post_name', $_POST['service_post_name']);
    update_user_meta($userid, 'service_info', $_POST['service_info']);
    update_user_meta($userid, 'service_address_line_1', $_POST['service_address_line_1']);
    //update_user_meta($userid, 'service_address_line_2', $_POST['service_address_line_2']);
        update_user_meta($userid, 'service_district', $_POST['service_district']);
    update_user_meta($userid, 'service_pin_code', $_POST['service_pin_code']);
    update_user_meta($userid, 'service_city', $_POST['service_city']);
    update_user_meta($userid, 'service_state', $_POST['service_state']);
    update_user_meta($userid, 'service_country', $_POST['service_country']);
    update_user_meta($userid, 'retire_type', $_POST['retire_type']);
    update_user_meta($userid, 'retire_title', $_POST['retire_title']);
    update_user_meta($userid, 'retire_post_name', $_POST['retire_post_name']);
    update_user_meta($userid, 'retire_info', $_POST['retire_info']);
    update_user_meta($userid, 'retire_address_line_1', $_POST['retire_address_line_1']);
    //update_user_meta($userid, 'retire_address_line_2', $_POST['retire_address_line_2']);
            update_user_meta($userid, 'retire_district', $_POST['retire_district']);
    update_user_meta($userid, 'retire_pin_code', $_POST['retire_pin_code']);
    update_user_meta($userid, 'retire_city', $_POST['retire_city']);
    update_user_meta($userid, 'retire_state', $_POST['retire_state']);
    update_user_meta($userid, 'retire_country', $_POST['retire_country']);
    update_user_meta($userid, 'social_type', $_POST['social_type']);
    update_user_meta($userid, 'social_title', $_POST['social_title']);
    update_user_meta($userid, 'social_work_as', $_POST['social_work_as']);
    update_user_meta($userid, 'social_info', $_POST['social_info']);
    update_user_meta($userid, 'social_address_line_1', $_POST['social_address_line_1']);
    //update_user_meta($userid, 'social_address_line_2', $_POST['social_address_line_2']);
                update_user_meta($userid, 'social_district', $_POST['social_district']);
    update_user_meta($userid, 'social_pin_code', $_POST['social_pin_code']);
    update_user_meta($userid, 'social_city', $_POST['social_city']);
    update_user_meta($userid, 'social_state', $_POST['social_state']);
    update_user_meta($userid, 'social_country', $_POST['social_country']);

    // If the current user can edit Users, allow this.
    update_usermeta( $userid, 'noone_meta', $_POST['noone_meta'] );
    update_usermeta( $userid, 'noone_upload_meta', $_POST['noone_upload_meta'] );
    update_usermeta( $userid, 'noone_upload_edit_meta', $_POST['noone_upload_edit_meta'] );
}
add_action('show_user_profile', 'noone_users');
add_action('edit_user_profile', 'noone_users');
add_action('personal_options_update', 'noone_users_save');
add_action('edit_user_profile_update', 'noone_users_save');
add_action( 'admin_enqueue_scripts', 'noone_enqueue_scripts_styles' );
function noone_enqueue_scripts_styles() {
    // Register
    wp_register_style( 'noone_admin_css', plugins_url( 'assets/css/styles.css', __FILE__), false, '1.0.0', 'all' );
    wp_register_script( 'noone_admin_js', plugins_url( 'assets/js/scripts.js', __FILE__ ), array('jquery'), '1.0.0', true );
    
    // Enqueue
    wp_enqueue_style( 'noone_admin_css' );
    wp_enqueue_script( 'noone_admin_js' );
}
/*add_action('wp_enqueue_scripts','noone_enqueue_init');

function noone_enqueue_init() {

	wp_enqueue_script('jquery');
    wp_enqueue_script('noone_google_map', 'http://maps.google.com/maps/api/js?sensor=false');
    wp_enqueue_script('noone_jquery_scroll', '//code.jquery.com/ui/1.11.2/jquery-ui.js');
     
	wp_enqueue_script('noone_gomap', plugins_url('assets/js/jquery.gomap-1.3.2.js', __FILE__));
	wp_enqueue_script('noone_js', plugins_url('assets/js/noone.js', __FILE__));
	wp_enqueue_style( 'noonecss', plugins_url('assets/css/noone.css', __FILE__) );    
	wp_enqueue_style( 'noonegridcss', plugins_url( 'assets/css/simplegrid.css', __FILE__));   
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox.js', '/'.WPINC.'/js/thickbox/thickbox.js', null, '1.0');
    wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0'); 
      
}*/
function remove_all_theme_styles() {
    global $wp_styles; 
    $wp_styles->queue = array();
    wp_enqueue_script('jquery');
    wp_enqueue_script('noone_google_map', 'http://maps.google.com/maps/api/js?sensor=false');
    wp_enqueue_script('noone_jquery_scroll', plugins_url('assets/js/jquery-ui.js', __FILE__));
    wp_enqueue_script('thickbox.js', '/'.WPINC.'/js/thickbox/thickbox.js', null, '1.0');
    wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0'); 
    wp_enqueue_script('noone_mCustomScrollbar', plugins_url('assets/js/jquery.mCustomScrollbar.js', __FILE__));
    wp_enqueue_script('noone_gomap', plugins_url('assets/js/jquery.gomap-1.3.3.js', __FILE__));
    wp_enqueue_script('noone_js', plugins_url('assets/js/noone.js', __FILE__));
    wp_enqueue_style('noonecss', plugins_url('assets/css/noone.css', __FILE__) );    
    wp_enqueue_style('noonegridcss', plugins_url( 'assets/css/bootstrap.css', __FILE__));
    wp_enqueue_style('mCustomScrollbarcss', plugins_url( 'assets/css/jquery.mCustomScrollbar.css', __FILE__));
    wp_enqueue_script('media-upload');

}

function remove_all_theme_styles_no_map() {
    global $wp_styles; 
    $wp_styles->queue = array();
    wp_enqueue_script('jquery');
    wp_enqueue_script('noone_jquery_scroll', plugins_url('assets/js/jquery-ui.js', __FILE__));
	wp_enqueue_script('thickbox.js', '/'.WPINC.'/js/thickbox/thickbox.js', null, '1.0');
    wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0'); 
    wp_enqueue_script('noone_js', plugins_url('assets/js/noone.js', __FILE__));
    wp_enqueue_script('noone_mCustomScrollbar', plugins_url('assets/js/jquery.mCustomScrollbar.js', __FILE__));
    wp_enqueue_style('noonecss', plugins_url('assets/css/noone.css', __FILE__) );    
    wp_enqueue_style('noonegridcss', plugins_url( 'assets/css/bootstrap.css', __FILE__));
    wp_enqueue_style('mCustomScrollbarcss', plugins_url( 'assets/css/jquery.mCustomScrollbar.css', __FILE__));
}
include('noone-searching-grid-pattern.php');
include('noone-map-searching-grid-pattern.php');
/**
 * Return an ID of an attachment by searching the database with the file URL.
 *
 * First checks to see if the $url is pointing to a file that exists in
 * the wp-content directory. If so, then we search the database for a
 * partial match consisting of the remaining path AFTER the wp-content
 * directory. Finally, if a match is found the attachment ID will be
 * returned.
 *
 * http://frankiejarrett.com/get-an-attachment-id-by-url-in-wordpress/
 *
 * @return {int} $attachment
 */
function get_attachment_image_by_url( $url ) {
 
    // Split the $url into two parts with the wp-content directory as the separator.
    $parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
 
    // Get the host of the current site and the host of the $url, ignoring www.
    $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
    $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
 
    // Return nothing if there aren't any $url parts or if the current host and $url host do not match.
    if ( !isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) ) {
        return;
    }
 
    // Now we're going to quickly search the DB for any attachment GUID with a partial path match.
    // Example: /uploads/2013/05/test-image.jpg
    global $wpdb;
 
    $prefix     = $wpdb->prefix;
    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );
    
    // Returns null if no attachment is found.
    return $attachment[0];
}

/**
 * Retrieve the appropriate image size
 *
 * @param $user_id    Default: $post->post_author. Will accept any valid user ID passed into this parameter.
 * @param $size       Default: 'thumbnail'. Accepts all default WordPress sizes and any custom sizes made by the add_image_size() function.
 * @return {url}      Use this inside the src attribute of an image tag or where you need to call the image url.
 */
function get_noone_meta( $user_id, $size ) {
	$default=plugins_url('assets/images/UserAvatar.png',__FILE__);
    //allow the user to specify the image size
    if (!$size){
        $size = 'thumbnail'; // Default image size if not specified.
    } 

    if(!$user_id){
        $user_id = $post->post_author;
    }
    
    // get the custom uploaded image
    $attachment_upload_url = esc_url( get_the_author_meta( 'noone_upload_meta', $user_id ) );
    
    // get the external image
    $attachment_ext_url = esc_url( get_the_author_meta( 'noone_meta', $user_id ) );
    $attachment_url = '';
    if($attachment_upload_url){
        $attachment_url = $attachment_upload_url;
    } elseif($attachment_ext_url) {
        $attachment_url = $attachment_ext_url;
    }
 
    // grabs the id from the URL using Frankie Jarretts function
    $attachment_id = get_attachment_image_by_url( $attachment_url );
 
    // retrieve the thumbnail size of our image
    $image_thumb = wp_get_attachment_image_src( $attachment_id, $size );
    if($image_thumb[0])
        return $image_thumb[0];
    else
            return $default;
}

function noone_gravatar_filter($avatar, $id_or_email, $size, $default, $alt) {
	$default=plugins_url('assets/images/UserAvatar.png',__FILE__);
	//allow the user to specify the image size
    if (!$size){
        $size = 'thumbnail'; // Default image size if not specified.
    }
    if(!$id_or_email){
        $id_or_email = $post->post_author;
    }
    
    // get the custom uploaded image
    $attachment_upload_url = esc_url( get_the_author_meta( 'noone_upload_meta', $id_or_email ) );
    
    // get the external image
    $attachment_ext_url = esc_url( get_the_author_meta( 'noone_meta', $id_or_email ) );
    $attachment_url = '';
    if($attachment_upload_url){
        $attachment_url = $attachment_upload_url;
    } elseif($attachment_ext_url) {
        $attachment_url = $attachment_ext_url;
    }
 
    // grabs the id from the URL using Frankie Jarretts function
    $attachment_id = get_attachment_image_by_url( $attachment_url );
 
    // retrieve the thumbnail size of our image
    $image_thumb = wp_get_attachment_image_src( $attachment_id, $size );
 
    // return the image thumbnail
    if($image_thumb[0])
        $return = '<img src="'.$image_thumb[0].'"  alt="'.$alt.'" width="50" />';
    else
         $return = '<img src="'.$default.'" width="'.$size.'" height="'.$size.'" alt="'.$alt.'" width="50" />';

    return $return;
}
add_filter('get_avatar', 'noone_gravatar_filter', 10, 5);


add_action( 'wp_ajax_getUserDetails', 'getUserDetails' );
add_action( 'wp_ajax_nopriv_getUserDetails', 'getUserDetails' );

function getUserDetails() {
    global $wpdb; // this is how you get access to the database

    require_once('user-detail.php');

    die(); // this is required to return a proper result
}
function country_drop_down($name, $selected)
{
    $countries = array(
        
        "IN" => "India"
    );
    ksort($countries);
?>
<select name="<?php
    echo $name;
?>" id="<?php
    echo $name;
?>">
<?php
    foreach ($countries as $key => $value)
    {
?>
   <option value="<?php
        echo $key;
?>" <?php
        selected($key, $selected);
?> title="<?php
        echo htmlspecialchars($value);
?>"><?php
        echo htmlspecialchars($value);
?></option>
    <?php
    } //$countries as $key => $value
?>
</select>
<?php
}

function send_message_user(){
  //get the data from ajax() call
  $headers = 'From: '.$_POST['name'].' <'.$_POST['from'].'>' . "\r\n";
  if(wp_mail($_POST['to'], $_POST['subject'], $_POST['user_message'], $headers)){
    die('1');
  }
  else{
    die('0');
  }
}
  // creating Ajax call for WordPress
   add_action( 'wp_ajax_nopriv_send_message_user', 'send_message_user' );
   add_action( 'wp_ajax_send_message_user', 'send_message_user' );

add_action( 'wp_ajax_district_action', 'district_action' );

   function district_action() {     
            global $wpdb;
            $distric=strtolower($_REQUEST['district']);
            
            $result = $wpdb->get_results("select * from ".$wpdb->prefix . "district_list where LOWER(district_name) like '%".$distric."%'" );   
            $data = "";
            echo '<ul id="district_list_ul">';
            $i=0;
            foreach($result as $dis)
            {
                $i++;
              echo '<li><a onclick="getval(this.id,\''.$dis->district_name.'\')" id="d'.$i.'" >'.$dis->district_name.'</a></li>';
            }
            echo '</ul>' ;  
            die();
            }
add_action( 'wp_ajax_nopriv_state_action', 'state_action' );
add_action( 'wp_ajax_state_action', 'state_action' );

   function state_action() {     
            global $wpdb;
            $state=strtolower($_REQUEST['state']);
            
            $result = $wpdb->get_results("select * from ".$wpdb->prefix . "states_list where LOWER(state_name) like '%".$state."%'" );   
            $data = "";
            echo '<ul id="state_list_ul">';
            $i=0;
            foreach($result as $dis)
            {
                $i++;
              echo '<li><a onclick="sgetval(this.id,\''.$dis->state_name.'\')" id="d'.$i.'" >'.$dis->state_name.'</a></li>';
            }
            echo '</ul>' ;  
            die();
            }
add_action( 'wp_ajax_nopriv_state_action_front', 'state_action_front' );
add_action( 'wp_ajax_state_action_front', 'state_action_front' );

   function state_action_front() {     
            global $wpdb;
            $state=strtolower($_REQUEST['state']);
            
            $result = $wpdb->get_results("select * from ".$wpdb->prefix . "states_list where LOWER(state_name) like '%".$state."%'" );   
            $data = "";
            echo '<div class="list-group" id="state_list_ul">';
            $i=0;
            foreach($result as $dis)
            {
                $i++;
              echo ' <a class="list-group-item" onclick="sgetval(this.id,\''.$dis->state_name.'\')" id="d'.$i.'" >'.$dis->state_name.'</a> ';
            }
            echo '</div>' ;  
            die();
            }
             
function add_br($str){
    return (trim($str)!='')? $str.'<br />':'';
}
function add_comma_br($str){
    return (trim($str)!='')? $str.',<br />':'';
}
function add_comma($str){
    return (trim($str)!='')? $str.',':'';
}

// Adding our plugin template in page template dropdown in WP Admin
class NoonePageTemplater {

		/**
         * A Unique Identifier
         */
		 protected $plugin_slug;

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin tracks.
         */
        protected $templates;


        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

                if( null == self::$instance ) {
                        self::$instance = new NoonePageTemplater();
                } 

                return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

                $this->templates = array();


                // Add a filter to the attributes metabox to inject template into the cache.
                add_filter(
					'page_attributes_dropdown_pages_args',
					 array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the save post to inject out template into the page cache
                add_filter(
					'wp_insert_post_data', 
					array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the template include to determine if the page has our 
				// template assigned and return it's path
                add_filter(
					'template_include', 
					array( $this, 'view_project_template') 
				);


                // Add your templates to this array.
                $this->templates = array(
                        'template/page-user-search.php'     => 'User Search Grid Pattern',
                        'template/page-user-map-search.php'     => 'User Search with Map Grid Pattern',
                );
				
        } 


        /**
         * Adds our template to the pages cache in order to trick WordPress
         * into thinking the template file exists where it doens't really exist.
         *
         */

        public function register_project_templates( $atts ) {

                // Create the key used for the themes cache
                $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

                // Retrieve the cache list. 
				// If it doesn't exist, or it's empty prepare an array
                $templates = wp_get_theme()->get_page_templates();
                if ( empty( $templates ) ) {
                        $templates = array();
                } 

                // New cache, therefore remove the old one
                wp_cache_delete( $cache_key , 'themes');

                // Now add our template to the list of templates by merging our templates
                // with the existing templates array from the cache.
                $templates = array_merge( $templates, $this->templates );

                // Add the modified cache to allow WordPress to pick it up for listing
                // available templates
                wp_cache_add( $cache_key, $templates, 'themes', 1800 );

                return $atts;

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function view_project_template( $template ) {

                global $post;

                if (!isset($this->templates[get_post_meta( 
					$post->ID, '_wp_page_template', true 
				)] ) ) {
					
                        return $template;
						
                } 

                $file = plugin_dir_path(__FILE__). get_post_meta( 
					$post->ID, '_wp_page_template', true 
				);
				
                // Just to be safe, we check if the file exist first
                if( file_exists( $file ) ) {
                        return $file;
                } 
				else { echo $file; }

                return $template;

        } 


} 

add_action( 'plugins_loaded', array( 'NoonePageTemplater', 'get_instance' ) );

function itr_global_js_vars() {
    $ajax_url = 'var nooneobject = {"nooneajaxurl":"'. admin_url( 'admin-ajax.php' ) .'", "nooneajaxnonce":"'. wp_create_nonce( 'itr_ajax_nonce' ) .'"};';
    echo "<script type='text/javascript'>\n";
    echo "/* <![CDATA[ */\n";
    echo $ajax_url;
    echo "\n/* ]]> */\n";
    echo "</script>\n";
}
 

?>
