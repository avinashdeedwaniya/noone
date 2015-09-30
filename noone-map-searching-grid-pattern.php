<?php
function noone_map_searching()
{
    global $wpdb;
    require_once('paging.class.php');
    $paging = new paging;
    $having_qry='';
    $distance_select='';
    $where_qry ='';
    $join='';
    # DISTANCE QUERY
    $order_by='display_name';
    if (isset($_REQUEST['geo']) && trim($_REQUEST['geo']) == 'on')
    {
        $distance_select=' , p.distance_unit
                 * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                 * COS(RADIANS(mt30.meta_value ))
                 * COS(RADIANS(p.longpoint) - RADIANS(mt31.meta_value))
                 + SIN(RADIANS(p.latpoint))
                 * SIN(RADIANS(mt30.meta_value)))) AS distance ';
        
        $join= ' JOIN (   
        SELECT  '.$_REQUEST['user_lat'].'  AS latpoint,  '.$_REQUEST['user_long'].' AS longpoint,
                '.$_REQUEST['geo-radius'].'.0 AS radius,      111.045 AS distance_unit
    ) AS p ON 1=1 ';

        $where_qry = '  AND (mt30.meta_value
     BETWEEN p.latpoint  - (p.radius / p.distance_unit)
         AND p.latpoint  + (p.radius / p.distance_unit)
    AND mt31.meta_value
     BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
         AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))) ';
      

        $having_qry=' having distance < '.$_REQUEST['geo-radius'];
        
        if(isset($_REQUEST['search_order']) && trim($_REQUEST['search_order']) != ''){
			
			$order_by=$_REQUEST['search_order'];
			
		}
    }

    $SQL    = "SELECT distinct(".$wpdb->prefix."users.ID) as ID,display_name  ".$distance_select." FROM ".$wpdb->prefix."users ".$join;
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
    $SQL .= " INNER JOIN ".$wpdb->prefix."usermeta AS mt2 ON (".$wpdb->prefix."users.ID = mt2.user_id) WHERE 1=1 ".$where_qry;

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
        OR
        (mt16.meta_key = 'self_info' AND LOWER(CAST(mt16.meta_value AS CHAR)) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
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
(mt2.meta_key = '".$wpdb->prefix."capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%subscriber%') ".$having_qry." ORDER BY '.$order_by.' ASC ";
    $t_record        = $wpdb->get_results($TSQL);
    $total_records   = count($t_record);
    $record_per_page = 2;
    $paged = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
    $paging->assign(get_permalink().'?search_name='.$_REQUEST['search_name'].'&search_city='.$_REQUEST['search_city'].'&search_state='.$_REQUEST['search_state'].'&search_sector='.$_REQUEST['search_sector'].'&search_occp_type='.$_REQUEST['search_occp_type'].'&search_occp_city='.$_REQUEST['search_occp_city'].'&search_occp_state='.$_REQUEST['search_occp_state'].'&search_btn='.$_REQUEST['search_btn'].'&dir-search='.$_REQUEST['dir-search'].'', $total_records, $record_per_page,$paged);
    $sql_limit = $paging->sql_limit();
   $SQL .= " AND 
(mt2.meta_key = '".$wpdb->prefix."capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%subscriber%') ".$having_qry." ORDER BY ".$order_by." ASC LIMIT " . $sql_limit; 
    $fivesdrafts = $wpdb->get_results($SQL);
    
?>
<div class="author-entry">
    <script src="<?php echo plugins_url('assets/js/jquery.validate.js', __FILE__)?>"></script> 

	<div id="mapnew" style="width:100%; clear:both;" ></div> 
	<div id="map-search-form">
		<form class="form-horizontal"  method="get" action="<?php  echo get_permalink(); ?>">
			  <input type="hidden" name="user_lat" value="<?php  echo $_REQUEST['user_lat']; ?>" id="user_lat">
			  <input type="hidden" id="user_long" value="<?php  echo $_REQUEST['user_long']; ?>" name="user_long">
			<div id="form_fileds" class="mCustomScrollbar">
				<p class="searchbox-title">Search By Person</p>
				  
						<input type="text" name="search_name" placeholder="Full Name" 
						id="search_name"  class="dir-searchinput form-control" value="<?php  echo $_REQUEST['search_name']; ?>" />
					 
					 <input type="text" placeholder="City" id="search_city" 
					class="ui-autocomplete-input form-control"   name="search_city" value="<?php
						echo $_REQUEST['search_city']; ?>" /> 
					 
					<input type="text" class="ui-autocomplete-input state form-control" placeholder="State" autocomplete="off" id="search_state" name="search_state" value="<?php
						echo $_REQUEST['search_state']; ?>" /> 
					<div class="skey"></div>
					 
					<div class="dir-searchinput-settings" id="dir-searchinput-settings">
						<p class="searchbox-title text">Search by Profession</p>
						 
							<select name="search_sector" class="form-control">
								<option value="" <?php
									selected($_REQUEST['search_sector'], ''); ?>>-- Job Sector --</option>
								<option value="govt" <?php
									selected($_REQUEST['search_sector'], 'govt'); ?>>Government</option>
								<option value="private" <?php
									selected($_REQUEST['search_sector'], 'private'); ?>>Private</option>
							</select>
						 
							<input type="text" name="search_occp_type" class="form-control" placeholder="Job Post or Info" value="<?php
							echo $_REQUEST['search_occp_type']; ?>" />
						 
							<input type="text" name="search_occp_city" class="form-control" placeholder="Job City" value="<?php
								echo $_REQUEST['search_occp_city']; ?>" /> 
						 
							<input type="text" name="search_occp_state" class="form-control" placeholder="Job State" value="<?php
							echo $_REQUEST['search_occp_state']; ?>" /> 
						 
					</div>
					<div class="dir-searchinput-settings" id="dir-searchinput-settings-position">
						<div id="dir-search-advanced">
							<p class="searchbox-title text">Search around my position</p>
							<div class="geo-loc" >
							<div class="search-slider-geo">
								<div class="geo-button">
									<?php
										echo $_REQUEST['geo'];if($_REQUEST['geo']=='on'){ ?>
									<input type="checkbox" checked="checked" id="dir-searchinput-geo" name="geo" class="hidden">
									<div class="iphone-style" rel="dir-searchinput-geo">&nbsp;</div>
									<?php  } else { ?>
										<input type="checkbox"   id="dir-searchinput-geo" name="geo" class="hidden">
									<div class="iphone-style off" rel="dir-searchinput-geo">&nbsp;</div>
										<?php
											} ?>
								</div>
								<div id="geo-slider"></div>
								<div class="text-geo-radius clear">
									<input type="hidden" value="<?php  echo $_REQUEST['geo-radius']; ?>" id="dir-searchinput-geo-radius" name="geo-radius">
									<div class="metric">10 km</div>
								</div>
							</div>
						</div>
						</div>
						</div>
					<div class="dir-searchinput-settings" id="dir-searchinput-settings">
						<div id="dir-search-advanced">
							<div class="searchbox-title order-by-text text">Show results in order by</div>
							 
								<select name="search_order" class="form-control" id="search_order">
									<option value="display_name" <?php
										selected($_REQUEST['search_order'], 'display_name'); ?>>Name</option>
									<option disabled="disabled" value="distance" <?php
										selected($_REQUEST['search_order'], 'distance'); ?>>Distance</option>
								</select>
							 
						</div>
					</div>  
			</div>
			<div id="dir-search-button">
				<input type="submit" class="btn btn-primary" value="Search" name="search_btn" id="dir-searchsubmit">
				<a class="btn btn-primary clear_search" id="clear_search">Clear Search</a>
				<a href="javascript:void(0);" class="hide_search_div">Hide filter &raquo;</a>
			</div>
			</form>
	</div>			
 
    <script type="text/javascript">
		
		jQuery(document).ready(function(){
			jQuery(".hide_search_div").on("click",function(){
				 
				if(jQuery("#map-search-form").css('right') == '0px'){
					jQuery("#map-search-form").animate({right: '-284px'},'slow',function(){
						jQuery(".hide_search_div").html("&laquo; Show filter");
					});
				}
				else{
					jQuery("#map-search-form").animate({right: '0'},'slow',function(){
						jQuery(".hide_search_div").html("Hide filter &raquo;");
					});
				}
			});
			
			jQuery(".clear_search").click(function(){
				window.location.href='<?php echo get_permalink();?>';
			});
            jQuery( "#geo-slider" ).slider({
                range: "max",
                min: 10,
                max: 500,
                value: <?php echo (isset($_REQUEST['geo-radius']) ? $_REQUEST['geo-radius'] : 10)?>,
                slide: function( event, ui ) {
                    jQuery( "#dir-searchinput-geo-radius" ).val( ui.value );
                    jQuery(".metric").html( ui.value+' Km' );
                    jQuery.goMap.removeOverlay('circle', 'riga');
                    var dlat = jQuery("#user_lat").val( ) ;
                    var dlong = jQuery("#user_long").val() ;
                    jQuery.goMap.createCircle({
                        id: 'riga',
                        latitude: dlat,
                        longitude: dlong,
                        radius: (ui.value * 1000)
                    });
                    
                }

            }).on( "slidestop", function( event, ui ) {
				//jQuery(".form-horizontal").submit();
			} );
                
            jQuery( "#dir-searchinput-geo-radius" ).val( jQuery( "#geo-slider" ).slider( "value" ) );
            jQuery(".metric").html( jQuery( "#geo-slider" ).slider( "value" )+' Km' );
		});
    jQuery(function() { 
		var dlat = jQuery("#user_lat").val( ) ;
        var dlong = jQuery("#user_long").val() ;
        jQuery("#mapnew").goMap({
			maptype: 'ROADMAP',
			scrollwheel: false,
			navigationControl:false,
			mapTypeControlOptions:{
				position: 'TOP_LEFT'
			},
			latitude: dlat, 
			longitude: dlong,
            zoom:<?php echo (isset($_REQUEST['geo-radius']) ? 5 : 10)?>,
            markers: [
            <?php
        $i = 0;
        foreach ($fivesdrafts as $author)
        {

            $i++;
            $author_info = get_userdata($author->ID);
  
            if (trim($author_info->perma_lat) != '' && trim($author_info->perma_long) != '')
            {
                 
                    $icon=plugins_url('assets/images/gmap_marker_blue.png',__FILE__);
                
                $map_arr[] = "{  
                        latitude: " . $author_info->perma_lat . ", 
                        longitude: " . $author_info->perma_long . ", 
                        id: 'map_" . $i . "', 
                        icon: '".$icon."',
						html: { content: '<div class=\"row\"><div class=\"col-xs-5\"><img src=\"".get_noone_meta($author_info->ID,'gomap_marker_html')."\" ></div><div class=\"col-xs-7\">".$author_info->first_name."<br/>".round($author->distance,2)." Km from you<br/><a href=\"javascript:void(0);\" onClick=\"javascript:info_show(\'".$author_info->first_name." ".$author_info->last_name."\',".$author_info->ID.",450,800);\" class=\"btn btn-primary btn-sm\">VIEW MORE</a></div></div>'}
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
<?php if(isset($_REQUEST['geo-radius']) && trim($_REQUEST['geo-radius'])!='' && isset($_REQUEST['geo']) && trim($_REQUEST['geo']) == 'on'){?>
					var dist = jQuery( "#dir-searchinput-geo-radius" ).val();
					
					//jQuery.goMap.removeOverlay('circle', 'riga');
                    var dlat = jQuery("#user_lat").val( ) ;
                    var dlong = jQuery("#user_long").val() ;
                    
                    jQuery.goMap.createMarker({
						latitude: dlat,
						longitude: dlong,
						id: 'position_marker', 
						title: 'You are here.',
						draggable: true,
						icon: '<?php echo plugins_url('assets/images/marker-person.png',__FILE__);?>',
						html: { content: 'You are here.'}
					});
					jQuery.goMap.createListener({type:'marker', marker:'position_marker'}, 'dragend', function() { 
						jQuery.goMap.removeOverlay('circle', 'riga');
						jQuery("#user_lat").val(this.getPosition().lat()) ;
						jQuery("#user_long").val(this.getPosition().lng()) ; 
						var radius_val = jQuery("#dir-searchinput-geo-radius").val();
						jQuery.goMap.createCircle({
							id: 'riga',
							latitude: this.getPosition().lat(),
							longitude: this.getPosition().lng(),
							radius: (radius_val * 1000)
						});
						jQuery(".form-horizontal").submit();
					});
					
                    jQuery.goMap.createCircle({
                        id: 'riga',
                        latitude: dlat,
                        longitude: dlong,
                        radius: (dist * 1000)
                    });
                    
                    
				
    
             <?php }?> 

}); 



</script>
 
<?php
if ($fivesdrafts)
    {
        ?>
    <div class="container">
        <div class="row search_data" style="margin-top:20px;">
            <div class="col-sm-12">Total recored found: <?php echo $total_records;?> </div>
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
            <li class="well"> <div class="panel panel-default">
                <div class="panel-heading"><?php echo $author_info->first_name.' '.$author_info->last_name; ?></div>
                <div class="panel-body">
        	     
         <?php
           echo '<div class="user_block row"><figure class=" "><img src="' . get_noone_meta($author->ID,'gomap_marker_html') . '" onClick="javascript:info_show(\''.$author_info->first_name.' '.$author_info->last_name.'\','.$author_info->ID.',450,800);" /></figure></div>';
            
           echo' <div class="row"><input type="button" class="btn btn-primary btn-block" onClick="javascript:info_show(\''.$author_info->first_name.' '.$author_info->last_name.'\','.$author_info->ID.',450,800);" value="View Full Info"></div>';

            if (trim($author_info->perma_lat) != '' && trim($author_info->perma_long) != '')
            {
?>
				<div class="row"><input type="button" class="gmap_button btn btn-primary btn-block" style="margin-top:10px;" id="map_but_<?php echo $i;?>" value="View on Map"></div>
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
	
					
					var ulat = jQuery("#user_lat").val() ;
					var ulong = jQuery("#user_long").val() ;	
					if(ulat!='' && ulong!=''){
						jQuery.goMap.removeOverlay('polyline','poly_linee');
						jQuery.goMap.createPolyline({
							color: "#00CC00",
							id: "poly_linee",
							opacity: 0.5,
							weight:	4,
							coords:	[{
									latitude: '<?php
								echo $author_info->perma_lat;?>',
									longitude: '<?php
								echo $author_info->perma_long;?>'
								} ,{ 
									latitude: ulat,
									longitude: ulong
							}]
						});
					}
					});
					
					 
				});
				</script>
            <?php
           
            }  
               
			echo'<div class="row icons text-center" >';
            echo '<a href="mailto:'.$author_info->user_email.'" class="email"></a>';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->user_twitter.'" class="twitter" target="_blank"></a>';
            if($author_info->user_fb_id)
                echo'<a href="'.$author_info->user_fb_id.'" class="facebook" target="_blank"></a>';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->linked_in.'" class="linkedin" target="_blank"></a>';
            if($author_info->user_twitter)
                echo'<a href="'.$author_info->google_plus.'" class="googleplus" target="_blank"></a>';
            echo'</div>';?>
        		
            </div>
              </div>
				<?php if (isset($_REQUEST['geo']) && trim($_REQUEST['geo']) == 'on')
						{
							if(isset($_REQUEST['search_order']) && trim($_REQUEST['search_order']) == 'distance'){
								echo "[ ".round($author->distance,2)." Km from you ]";
							}
						}
				?>
            </li>
        <?php
        } //$fivesdrafts as $author
       ?>
        </ul> <div class="modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="javascript:info_remove();">×</button>
        <h4 class="modal-title">Loading Title</h4>
      </div>
      <div class="modal-body">
        <p>Loading Information…</p>
      </div>
       
    </div>
  </div>
</div>
    </div>
   <div class="container"  style="margin-top:20px;">
        <div class="row">
            <div class="col-xs-12 text-center">
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
   <div class="container">
        <div class="row search_data" style="margin-top:20px;">
			<div class="col-sm-12">Apologies, but no results were found for the request. </div>
		</div> 
	</div>
    <?php
    }
    ?>
</div>
<?php    
}
add_shortcode('noone_map_searching', 'noone_map_searching'); 
?>
