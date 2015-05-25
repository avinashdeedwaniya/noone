<?php
function noone_map_searching()
{
    global $wpdb;
    require_once('paging.class.php');
    $paging = new paging;
    $having_qry='';
    $distance_select='';
    # DISTANCE QUERY
    if (isset($_REQUEST['geo']) && trim($_REQUEST['geo']) == 'on')
    {
        $distance_select=' ,( 3959 * acos( cos( radians('.$_REQUEST['user_lat'].') ) * cos( radians( mt30.meta_value ) ) * cos( radians( mt31.meta_value ) - radians('.$_REQUEST['user_long'].') ) + sin( radians('.$_REQUEST['user_lat'].') ) * sin( radians( mt30.meta_value ) ) ) ) AS distance';
        $having_qry=' HAVING distance < '.$_REQUEST['geo-radius'];
    }

    $SQL    = "SELECT distinct(".$wpdb->prefix."users.ID) as ID,display_name  ".$distance_select." FROM ".$wpdb->prefix."users";
    if (isset($_REQUEST['search_name']) && trim($_REQUEST['search_name']) != '')
    {
        $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta ON (".$wpdb->prefix."users.ID = ".$wpdb->prefix."usermeta.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt1 ON (".$wpdb->prefix."users.ID = mt1.user_id) ";
    }
    if (isset($_REQUEST['search_city']) && trim($_REQUEST['search_city']) != '')
    {
        $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt3 ON (".$wpdb->prefix."users.ID = mt3.user_id)
        ";
    }
    if (isset($_REQUEST['search_occp_city']) && trim($_REQUEST['search_occp_city']) != '')
    {
        $SQL .= "INNER JOIN ".$wpdb->prefix."usermeta AS mt5 ON (".$wpdb->prefix."users.ID = mt5.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt6 ON (".$wpdb->prefix."users.ID = mt6.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt26 ON (".$wpdb->prefix."users.ID = mt26.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt27 ON (".$wpdb->prefix."users.ID = mt27.user_id) ";
    }
    if (isset($_REQUEST['search_state']) && trim($_REQUEST['search_state']) != '')
    {
        $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt7 ON (".$wpdb->prefix."users.ID = mt7.user_id)
         ";
    }
    if (isset($_REQUEST['search_occp_state']) && trim($_REQUEST['search_occp_state']) != '')
    {
        $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt9 ON (".$wpdb->prefix."users.ID = mt9.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt10 ON (".$wpdb->prefix."users.ID = mt10.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt28 ON (".$wpdb->prefix."users.ID = mt28.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt29 ON (".$wpdb->prefix."users.ID = mt29.user_id) ";
    }
    if (isset($_REQUEST['search_sector']) && trim($_REQUEST['search_sector']) != '')
    {
        $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt11 ON (".$wpdb->prefix."users.ID = mt11.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt12 ON (".$wpdb->prefix."users.ID = mt12.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt13 ON (".$wpdb->prefix."users.ID = mt13.user_id) ";
    }
    if (isset($_REQUEST['search_occp_type']) && trim($_REQUEST['search_occp_type']) != '')
    {
        $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt14 ON (".$wpdb->prefix."users.ID = mt14.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt15 ON (".$wpdb->prefix."users.ID = mt15.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt16 ON (".$wpdb->prefix."users.ID = mt16.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt17 ON (".$wpdb->prefix."users.ID = mt17.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt18 ON (".$wpdb->prefix."users.ID = mt18.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt19 ON (".$wpdb->prefix."users.ID = mt19.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt20 ON (".$wpdb->prefix."users.ID = mt20.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt21 ON (".$wpdb->prefix."users.ID = mt21.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt22 ON (".$wpdb->prefix."users.ID = mt22.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt23 ON (".$wpdb->prefix."users.ID = mt23.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt24 ON (".$wpdb->prefix."users.ID = mt24.user_id)
        INNER JOIN ".$wpdb->prefix."usermeta AS mt25 ON (".$wpdb->prefix."users.ID = mt25.user_id) ";
    } 

    if (isset($_REQUEST['geo']) && trim($_REQUEST['geo']) == 'on')
    {
        $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt30 ON (".$wpdb->prefix."users.ID = mt30.user_id and `mt30`.`meta_key`='perma_lat')";
        $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt31 ON (".$wpdb->prefix."users.ID = mt31.user_id and `mt31`.`meta_key`='perma_long')";
    }
    $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt2 ON (".$wpdb->prefix."users.ID = mt2.user_id) WHERE 1=1 ";

    // START WHERE CONDITIONS

    if (isset($_REQUEST['search_name']) && trim($_REQUEST['search_name']) != '')
    {
        $SQL .= "AND  
        (
            (".$wpdb->prefix."usermeta.meta_key = 'first_name' AND LOWER(CAST(".$wpdb->prefix."usermeta.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_name']) . "%')
            OR
            (mt1.meta_key = 'last_name' AND LOWER(CAST(mt1.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_name']) . "%')
            ) ";
    }
    if (isset($_REQUEST['search_city']) && trim($_REQUEST['search_city']) != '')
    {
        $SQL .= "AND  
    (
        (mt3.meta_key = 'city' AND LOWER(CAST(mt3.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_city']) . "%') 
    )";
    }
    if (isset($_REQUEST['search_state']) && trim($_REQUEST['search_state']) != '')
    {
        $SQL .= "AND  
    (
        (mt7.meta_key = 'state' AND LOWER(CAST(mt7.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_state']) . "%') 
    )";
    }
    if (isset($_REQUEST['search_sector']) && trim($_REQUEST['search_sector']) != '')
    {
        $SQL .= "AND  
    (
        (mt11.meta_key = 'service_type' AND LOWER(CAST(mt11.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_sector']) . "%') 
        OR
        (mt12.meta_key = 'retire_type' AND LOWER(CAST(mt12.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_sector']) . "%')
        OR
        (mt13.meta_key = 'social_type' AND LOWER(CAST(mt13.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_sector']) . "%')
        ) ";
    }
    if (isset($_REQUEST['search_occp_city']) && trim($_REQUEST['search_occp_city']) != '')
    {
        $SQL .= "AND  
    (
        (mt5.meta_key = 'self_city' AND LOWER(CAST(mt5.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_city']) . "%') 
        OR
        (mt6.meta_key = 'service_city' AND LOWER(CAST(mt6.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_city']) . "%')
        )
        OR
        (mt26.meta_key = 'retire_city' AND LOWER(CAST(mt26.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_city']) . "%')
        )
        OR
        (mt27.meta_key = 'social_city' AND LOWER(CAST(mt27.meta_value AS CHAR)) LIKE '%" .strtolower( $_REQUEST['search_occp_city']) . "%')
        ) ";
    }
    if (isset($_REQUEST['search_occp_state']) && trim($_REQUEST['search_occp_state']) != '')
    {
        $SQL .= "AND  
    (
        (mt9.meta_key = 'self_state' AND LOWER(CAST(mt9.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_state']) . "%') 
        OR
        (mt10.meta_key = 'service_state' AND LOWER(CAST(mt10.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_state']) . "%')
        
        OR
        (mt28.meta_key = 'retire_state' AND LOWER(CAST(mt28.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_state']) . "%')
        
        OR
        (mt29.meta_key = 'social_state' AND LOWER(CAST(mt29.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_state']) . "%')
        ) ";
    }
    if (isset($_REQUEST['search_occp_type']) && trim($_REQUEST['search_occp_type']) != '')
    {
        $SQL .= "AND  
        (
        (mt14.meta_key = 'self_title' AND LOWER(CAST(mt14.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%') 
        OR
        (mt15.meta_key = 'self_service' AND LOWER(CAST(mt15.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        )
        OR
        (mt16.meta_key = 'self_info' AND LOWER(CAST(mt16.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        )
        OR
        (mt17.meta_key = 'service_title' AND LOWER(CAST(mt17.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        OR
        (mt18.meta_key = 'service_post_name' AND LOWER(CAST(mt18.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        OR
        (mt19.meta_key = 'service_info' AND LOWER(CAST(mt19.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        OR
        (mt20.meta_key = 'retire_title' AND LOWER(CAST(mt20.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        OR
        (mt21.meta_key = 'retire_post_name' AND LOWER(CAST(mt21.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        OR
        (mt22.meta_key = 'retire_info' AND LOWER(CAST(mt22.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        OR
        (mt23.meta_key = 'social_title' AND LOWER(CAST(mt23.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        OR
        (mt24.meta_key = 'social_work_as' AND LOWER(CAST(mt24.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        OR
        (mt25.meta_key = 'social_info' AND LOWER(CAST(mt25.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
        ) ";
    }
   $TSQL            = $SQL . " AND
(mt2.meta_key = '".$wpdb->prefix."capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%subscriber%') ".$having_qry." ORDER BY display_name ASC ";
    $t_record        = $wpdb->get_results($TSQL);
    $total_records   = count($t_record);
    $record_per_page = 16;
    $paged = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
    $paging->assign(get_permalink().'?search_name='.$_REQUEST['search_name'].'&search_city='.$_REQUEST['search_city'].'&search_state='.$_REQUEST['search_state'].'&search_sector='.$_REQUEST['search_sector'].'&search_occp_type='.$_REQUEST['search_occp_type'].'&search_occp_city='.$_REQUEST['search_occp_city'].'&search_occp_state='.$_REQUEST['search_occp_state'].'&search_btn='.$_REQUEST['search_btn'].'&dir-search='.$_REQUEST['dir-search'].'', $total_records, $record_per_page,$paged);
    $sql_limit = $paging->sql_limit();
    $SQL .= " AND 
(mt2.meta_key = '".$wpdb->prefix."capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%subscriber%') ".$having_qry." ORDER BY display_name ASC LIMIT " . $sql_limit;
    $fivesdrafts = $wpdb->get_results($SQL);
    
?>
<div class="author-entry">
    <script src="<?php echo plugins_url('assets/js/jquery.validate.js', __FILE__)?>"></script> 

   <div id="mapnew" style="width:100%; clear:both;" ></div> 
   <div data-interactive="yes" class="map-search" id="directory-search">
					<div class="wrapper">
						<form class="dir-searchform"  method="get" action="<?php echo get_permalink();?>"
						 id="dir-search-form" 
						 >
						      <input type="hidden" name="user_lat" value="<?php echo $_REQUEST['user_lat'];?>" id="user_lat">
                              <input type="hidden" id="user_long" value="<?php echo $_REQUEST['user_long'];?>" name="user_long">
							<p class="searchbox-title">Search By Person</p>
							<div id="dir-search-inputs">
								<div id="dir-holder">
									<div class="dir-holder-wrap">
										<input type="text" name="search_name" placeholder="Full Name" 
										id="search_name"  class="dir-searchinput" value="<?php echo $_REQUEST['search_name'];?>" />
									 

									 <input type="text" placeholder="City" id="search_city" 
									class="ui-autocomplete-input"   name="search_city" value="<?php
    echo $_REQUEST['search_city'];
?>" />
									<input type="text" class="ui-autocomplete-input state" placeholder="State" autocomplete="off" id="search_state" name="search_state" value="<?php
    echo $_REQUEST['search_state'];
?>" /> <div class="skey"></div>

									</div>
								</div>
							</div>
							<div class="dir-searchinput-settings" id="dir-searchinput-settings">
										<div id="dir-search-advanced">
											<div class="searchbox-title text">Search by Profession</div>

											<div class="search-slider-geo">
												<select name="search_sector">
                <option value="" <?php
    selected($_REQUEST['search_sector'], '');
?>>-- Job Sector --</option>
                <option value="govt" <?php
    selected($_REQUEST['search_sector'], 'govt');
?>>Government</option>
                <option value="private" <?php
    selected($_REQUEST['search_sector'], 'private');
?>>Private</option>
            </select>
             <input type="text" name="search_occp_type" placeholder="Job Post or Info" value="<?php
    echo $_REQUEST['search_occp_type'];
?>" /> 

            <input type="text" name="search_occp_city" placeholder="Job City" value="<?php
    echo $_REQUEST['search_occp_city'];
?>" /> 

            <input type="text" name="search_occp_state" placeholder="Job State" value="<?php
    echo $_REQUEST['search_occp_state'];
?>" /> 
											</div>
										</div>
									</div> 
                                    <div class="dir-searchinput-settings" id="dir-searchinput-settings-position">
                                        <div id="dir-search-advanced">
                                            <div class="searchbox-title text">Search around my position</div>

                                            <div class="search-slider-geo">
                                                <div class="geo-button">
                                                    <?php
                                                    echo $_REQUEST['geo'];
                                                    if($_REQUEST['geo']=='on'){
                                                    ?>
                                                    <input type="checkbox" checked="checked" id="dir-searchinput-geo" name="geo" class="hidden">
                                                    <div class="iphone-style" rel="dir-searchinput-geo">&nbsp;</div>
                                                    <?php }else{
                                                        ?>
                                                        <input type="checkbox"   id="dir-searchinput-geo" name="geo" class="hidden">
                                                    <div class="iphone-style off" rel="dir-searchinput-geo">&nbsp;</div>
                                                        <?php
                                                    } ?>
                                                </div>

                                                <div id="geo-slider"></div>
                                                <div class="text-geo-radius clear">
                                                    <input type="text" value="<?php echo $_REQUEST['geo-radius'];?>" id="dir-searchinput-geo-radius" name="geo-radius">
                                                    <div class="metric">km</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
							<div id="dir-search-button">
							 
								<input type="submit" class="btn btn-info btn-block" value="Search" name="search_btn" id="dir-searchsubmit">
							</div>
							<input type="hidden" value="yes" name="dir-search">
							
						</form>
					</div>
				</div>
 				
 
    <script type="text/javascript">
		function equalizer(){
			var highestBox = 0;
			jQuery('.user_block').each(function(){
				jQuery(this).css("height","");
				if(jQuery(this).height() > highestBox) 
				   highestBox = jQuery(this).height(); 
			});  
			jQuery('.user_block').height(highestBox);
		}
		function info_show(name,uid,height,width){
				var ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"; 
                var data ={ action: "getUserDetails",  user_id:uid    };
                jQuery.post(ajaxurl, data, function (response){
                    jQuery(".TB_window").html(response);
                    
                });
                jQuery("#TB_overlay").show();
                jQuery(".TB_window").show();
                jQuery(".TB_window").css("height", height);
				//jQuery(".TB_window").css("width", '100%');
				jQuery(".TB_window").css("top", (jQuery(window).scrollTop()+20 ));
				//jQuery(".TB_window").css("left", (jQuery(window).width()-width)/2);
				jQuery(".TB_window").css("z-index",'100051');
				jQuery(".TB_window").css("position",'absolute');
				
				/* var TB_WIDTH = width, TB_HEIGHT = height;
				 // set the new width and height dimensions here..
				jQuery(".TB_window").animate({marginLeft: '"'+parseInt(((jQuery(window).width()-TB_WIDTH) / 2),10)
				 + 'px"', width: TB_WIDTH + 'px', height: TB_HEIGHT + 'px',marginTop:'"'+parseInt(((jQuery(window).height()-TB_HEIGHT) / 2),10) + 
				'px"'});*/
                
			}
			function info_remove(){
				jQuery("#TB_overlay").hide();
                jQuery(".TB_window").hide();
                jQuery(".TB_window").html('');
			}
		jQuery(document).ready(function(){
			jQuery("#TB_overlay").hide();
			 jQuery(".TB_window").hide();
			var map_height = jQuery( window ).height();
			jQuery("#mapnew").css("height",(map_height-20));
			equalizer();
			
			jQuery( window ).resize(function() {
			 equalizer();
			});
			jQuery( window ).on( "orientationchange", function( event ) {
				equalizer();
			});
            jQuery( "#geo-slider" ).slider({
                range: "max",
                min: 0,
                max: 100,
                value: <?php echo (isset($_REQUEST['geo-radius']) ? $_REQUEST['geo-radius'] : 10)?>,
                slide: function( event, ui ) {
                    jQuery( "#dir-searchinput-geo-radius" ).val( ui.value );
                }

            });
            jQuery( "#dir-searchinput-geo-radius" ).val( jQuery( "#geo-slider" ).slider( "value" ) );
		});
    jQuery(function() { 
        jQuery("#mapnew").goMap({
			maptype: 'ROADMAP',
            zoom:10,
            markers: [
            <?php
        $i = 0;
        foreach ($fivesdrafts as $author)
        {

            $i++;
            $author_info = get_userdata($author->ID);
  
            if (trim($author_info->perma_lat) != '' && trim($author_info->perma_long) != '')
            {
                if($i%2==0){
                    $icon=plugins_url('assets/images/pin-clubs.png',__FILE__);
                }
                elseif($i%3==0){
                     $icon=plugins_url('assets/images/pin-juices.png',__FILE__);
                }
                else{
                    $icon=plugins_url('assets/images/pin-muiffins.png',__FILE__);
                }
                $map_arr[] = "{  
                        latitude: " . $author_info->perma_lat . ", 
                        longitude: " . $author_info->perma_long . ", 
                        id: 'map_" . $i . "', 
                        icon: '".$icon."',
							shadow: 'http://preview.ait-themes.com/businessfinder/wp1/wp-content/themes/businessfinder/design/img/map-icon/icon-shadow.png',
                        html: { content: '<div class=\"marker-holder\"><div class=\"marker-content with-image\"><img src=\"".get_noone_meta($author_info->ID,'gomap_marker_html')."\" ><div class=\"map-item-info\"><div class=\"title\">".$author_info->first_name." ".$author_info->last_name."</div><div class=\"address\">".add_comma_br(str_replace('/','-', $author_info->address_line_1." ".$author_info->address_line_2)).add_comma($author_info->city).add_comma_br($author_info->state).$author_info->country."</div><a href=\"javascript:void(0);\" onClick=\"javascript:info_show(\'".$author_info->first_name." ".$author_info->last_name."\',".$author_info->ID.",450,800);\" class=\"more-button\">VIEW MORE</a></div><div class=\"arrow\" ></div></div></div>'}
                        }";
            } 
        } 
         
        if(!empty($map_arr))
            echo implode(',', $map_arr);
        else
            echo'';
?>
           ] 
        }); 


}); 



</script>
 
<?php
if ($fivesdrafts)
    {
        ?>
    <div class="container">
        <div class="row search_data" style="margin-top:20px;">
        <div class="col-sm-2">You are searching: </div>
        <div class="col-sm-10">
            <?php if (isset($_REQUEST['search_name']) && trim($_REQUEST['search_name']) != '')
            {?>
            <div class="col-sm-3"><?php echo $_REQUEST['search_name'];?> </div>
            <?php }
            if (isset($_REQUEST['search_city']) && trim($_REQUEST['search_city']) != '')
                {?>
            <div class="col-sm-3">in <?php echo $_REQUEST['search_city'];?> </div>
            <?php }?>
            <?php if (isset($_REQUEST['search_state']) && trim($_REQUEST['search_state']) != '')
                {?>
            <div class="col-sm-3">in <?php echo $_REQUEST['search_state'];?> </div>
            <?php }?>
             
        </div>
        </div>
        <ul class="ul_user" > 
 <?php       $i = 0;
        foreach ($fivesdrafts as $author)
        {
            $i++;
            $author_info = get_userdata($author->ID);
?>
           <li> 
        	     
         <?php
           echo '<div class="user_block"><figure class=" "><img src="' . get_noone_meta($author->ID,'gomap_marker_html') . '" onClick="javascript:info_show(\''.$author_info->first_name.' '.$author_info->last_name.'\','.$author_info->ID.',450,800);" /></figure></div>';
           echo '<h3 class="text-primary" onClick="javascript:info_show(\''.$author_info->first_name.' '.$author_info->last_name.'\','.$author_info->ID.',450,800);">'.$author_info->first_name.' '.$author_info->last_name.'</h3>'; 
           echo' <input type="button" class="btn btn-info btn-block" onClick="javascript:info_show(\''.$author_info->first_name.' '.$author_info->last_name.'\','.$author_info->ID.',450,800);" value="View Full Info"> ';

            if (trim($author_info->perma_lat) != '' && trim($author_info->perma_long) != '')
            {
?>
				<input type="button" class="gmap_button btn btn-info btn-block" id="map_but_<?php echo $i;?>" value="View on Map">
				<script type="text/javascript">
				jQuery(function(){
				 jQuery("#map_but_<?php
					echo $i;
	?>").click(function(){  
						jQuery.goMap.setMap({latitude:'<?php
					echo $author_info->perma_lat;?>', longitude:'<?php
					echo $author_info->perma_long;?>'

	}); 
						jQuery.goMap.setMap({zoom: 7});
						google.maps.event.trigger(jQuery(jQuery.goMap.mapId).data('map_<?php
					echo $i;
	?>'), 'click'); 
	jQuery( window ).scrollTop(0);
					}); 
				});
				</script>
            <?php
           
            }  
               
			echo'<span class="col-1-1 mobile-col-1-1 icons" >';
            echo '<a href="mailto:'.$author_info->user_email.'" class="email col-1-5"></a>';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->user_twitter.'" class="twitter col-1-5" target="_blank"></a>';
            if($author_info->user_fb_id)
                echo'<a href="'.$author_info->user_fb_id.'" class="facebook col-1-5" target="_blank"></a>';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->linked_in.'" class="linkedin col-1-5" target="_blank"></a>';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->google_plus.'" class="googleplus col-1-5" target="_blank"></a>';
            echo'</span>';?>
        		
            </li>
        <?php
        } //$fivesdrafts as $author
        echo'<div class="user_info_div">
                <div id="TB_overlay" class="TB_overlayBG"></div>
                <div class="TB_window"></div>
             </div>';?>
        </ul>
    </div>
 
    <div class="container"  style="margin-top:20px;">
        <div class="row">
            <div class="col-xs-12">
                <ul class="pagination">
                    <?php echo $paging->fetch();?>
                </ul>
            </div>
        </div>
    </div>
<?php
    } //$fivesdrafts
    else
    {
?>
   <h2>Apologies, but no results were found for the request.</h2>
    <?php
    }
    ?>
</div>
<?php    
}
add_shortcode('noone_map_searching', 'noone_map_searching'); 
?>
