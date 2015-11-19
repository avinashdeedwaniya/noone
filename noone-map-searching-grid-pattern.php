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
					 * COS(RADIANS(up.perma_lat ))
					 * COS(RADIANS(p.longpoint) - RADIANS(up.perma_long))
					 + SIN(RADIANS(p.latpoint))
					 * SIN(RADIANS(up.perma_lat)))) AS distance ';
			
			$join= ' JOIN (   
			SELECT  '.$_REQUEST['user_lat'].'  AS latpoint,  '.$_REQUEST['user_long'].' AS longpoint,
					'.$_REQUEST['geo-radius'].'.0 AS radius,      111.045 AS distance_unit
		) AS p ON 1=1 ';
		
		$where_qry = '  AND (up.perma_lat
		 BETWEEN p.latpoint  - (p.radius / p.distance_unit)
			 AND p.latpoint  + (p.radius / p.distance_unit)
		AND up.perma_long
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
		if ((isset($_REQUEST['search_city']) && trim($_REQUEST['search_city']) != '') || (isset($_REQUEST['search_state']) && trim($_REQUEST['search_state']) != '') || (isset($_REQUEST['geo']) && trim($_REQUEST['geo']) == 'on'))
		{
			$SQL .= " INNER JOIN ".$wpdb->prefix."user_personal AS up ON (".$wpdb->prefix."users.ID = up.user_id)
			";
		}
		if ((isset($_REQUEST['search_occp_city']) && trim($_REQUEST['search_occp_city']) != '') || 
		(isset($_REQUEST['search_occp_state']) && trim($_REQUEST['search_occp_state']) != '') ||
		(isset($_REQUEST['search_occp_type']) && trim($_REQUEST['search_occp_type']) != '') ||
		(isset($_REQUEST['search_sector']) && trim($_REQUEST['search_sector']) != '')
		)
		{
			 $SQL .= " 	LEFT JOIN ".$wpdb->prefix."user_self AS slf ON (".$wpdb->prefix."users.ID = slf.user_id)
						LEFT JOIN ".$wpdb->prefix."user_service AS ser ON (".$wpdb->prefix."users.ID = ser.user_id)
						LEFT JOIN ".$wpdb->prefix."user_retire AS ret ON (".$wpdb->prefix."users.ID = ret.user_id)
						LEFT JOIN ".$wpdb->prefix."user_social AS soc ON (".$wpdb->prefix."users.ID = soc.user_id) ";
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
			$SQL .= "AND (LOWER(up.city) LIKE '%" . strtolower($_REQUEST['search_city']) . "%')";
		}
		if (isset($_REQUEST['search_state']) && trim($_REQUEST['search_state']) != '')
		{
			$SQL .= "AND (LOWER(up.state) LIKE '%" . strtolower($_REQUEST['search_state']) . "%') ";
		}
		if (isset($_REQUEST['search_sector']) && trim($_REQUEST['search_sector']) != '')
		{
			$SQL .= "AND  
			(
				(LOWER(ser.service_type) LIKE '%" . strtolower($_REQUEST['search_sector']) . "%')
				OR
				(LOWER(ret.retire_type) LIKE '%" . strtolower($_REQUEST['search_sector']) . "%') 
				OR
				(LOWER(soc.social_type) LIKE '%" . strtolower($_REQUEST['search_sector']) . "%') 
			 ) ";
		}
		if (isset($_REQUEST['search_occp_city']) && trim($_REQUEST['search_occp_city']) != '')
		{
			$SQL .= "AND  
			(
				(LOWER(slf.self_city) LIKE '%" . strtolower($_REQUEST['search_occp_city']) . "%')
				OR
				(LOWER(ser.service_city) LIKE '%" . strtolower($_REQUEST['search_occp_city']) . "%')
				OR
				(LOWER(ret.retire_city) LIKE '%" . strtolower($_REQUEST['search_occp_city']) . "%')
				OR
				(LOWER(soc.social_city) LIKE '%" . strtolower($_REQUEST['search_occp_city']) . "%')
			 ) ";
		}
		if (isset($_REQUEST['search_occp_state']) && trim($_REQUEST['search_occp_state']) != '')
		{
			$SQL .= "AND  
			(
				(LOWER(slf.self_state) LIKE '%" . strtolower($_REQUEST['search_occp_state']) . "%')
				OR
				(LOWER(ser.service_state) LIKE '%" . strtolower($_REQUEST['search_occp_state']) . "%')
				OR
				(LOWER(ret.retire_state) LIKE '%" . strtolower($_REQUEST['search_occp_state']) . "%')
				OR
				(LOWER(soc.social_state) LIKE '%" . strtolower($_REQUEST['search_occp_state']) . "%')
			) ";
		}
		if (isset($_REQUEST['search_occp_type']) && trim($_REQUEST['search_occp_type']) != '')
		{
			$SQL .= "AND  
			(
				(LOWER(slf.self_title) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(slf.self_service) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(slf.self_info) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(ser.service_title) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(ser.service_post_name) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(ser.service_info) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(ret.retire_title) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(ret.retire_post_name) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(ret.retire_info) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(soc.social_title) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(soc.social_work_as) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
				OR
				(LOWER(soc.social_info) LIKE '%" . strtolower($_REQUEST['search_occp_type']) . "%')
			) ";
		}
	   $TSQL            = $SQL . " AND
	(mt2.meta_key = '".$wpdb->prefix."capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%subscriber%') ".$having_qry." ORDER BY '.$order_by.' ASC ";
		$t_record        = $wpdb->get_results($TSQL);
		$total_records   = count($t_record);
		$record_per_page = 12;
		$paged = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
		$paging->assign(get_permalink().'?search_name='.$_REQUEST['search_name'].'&search_city='.$_REQUEST['search_city'].'&search_state='.$_REQUEST['search_state'].'&search_sector='.$_REQUEST['search_sector'].'&search_occp_type='.$_REQUEST['search_occp_type'].'&search_occp_city='.$_REQUEST['search_occp_city'].'&search_occp_state='.$_REQUEST['search_occp_state'].'&search_btn='.$_REQUEST['search_btn'].'&dir-search='.$_REQUEST['dir-search'].'', $total_records, $record_per_page,$paged);
		$sql_limit = $paging->sql_limit();
		$SQL .= " AND 
	(mt2.meta_key = '".$wpdb->prefix."capabilities' AND CAST(mt2.meta_value AS CHAR) LIKE '%subscriber%') ".$having_qry." ORDER BY ".$order_by." ASC LIMIT " . $sql_limit; 
		$fivesdrafts = $wpdb->get_results($SQL);
	?>
	<div class="author-entry">
		<script src="<?php echo plugins_url('assets/js/jquery.validate.js', __FILE__)?>"></script> 

	<ul class="margin_padding_0" > 
		<li class="well margin_padding_0" >
			<div class="panel panel-default margin_padding_0" >
				<div class="panel-body">
					<div id="mapnew" style="width:100%; clear:both;" ></div> 
					<ul class="margin_padding_0"  >
			<li class="well sform_li margin_padding_0" style="list-style:none;" id="map-search-form">
				<div class="panel panel-default margin_padding_0">
					<div class="panel-body">
						<form class="form-horizontal"  method="get" action="<?php  echo get_permalink(); ?>">
				<input type="hidden" name="user_lat" value="<?php  echo $_REQUEST['user_lat']; ?>" id="user_lat">
				<input type="hidden" id="user_long" value="<?php  echo $_REQUEST['user_long']; ?>" name="user_long">
				<div id="form_fileds"><!--   class="mCustomScrollbar" -->
					<p class="searchbox-title search_personal_p active">Search By Person</p>
					<div class="search_personal">
						<input type="text" name="search_name" placeholder="Full Name" id="search_name"  class="dir-searchinput form-control" value="<?php  echo $_REQUEST['search_name']; ?>" />
						<input type="text" placeholder="City" id="search_city" class="ui-autocomplete-input form-control"   name="search_city" value="<?php	echo $_REQUEST['search_city']; ?>" /> 
						<input type="text" class="state form-control" placeholder="State" autocomplete="off" id="search_state" name="search_state" value="<?php echo $_REQUEST['search_state']; ?>" /> 
						<div class="skey"></div>
					</div>
					<div class="dir-searchinput-settings" id="dir-searchinput-settings">
						<p class="searchbox-title text search_profession_p">Search by Profession</p>
							<div class="search_profession">
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
								<input type="text" name="search_occp_state" class="form-control state" placeholder="Job State" value="<?php
								echo $_REQUEST['search_occp_state']; ?>" /> 
								<div class="skey"></div>
							</div>
					</div>
					<div class="dir-searchinput-settings" id="dir-searchinput-settings-position">
						<div id="dir-search-advanced">
							<p class="searchbox-title text search_around_p">Search around me</p>
							<div class="search_around">
								<div class="geo-loc" >
									<div class="search-slider-geo">
										<div class="geo-button">
											<?php if($_REQUEST['geo']=='on'){ ?>
											<input type="checkbox" checked="checked" id="dir-searchinput-geo" name="geo" class="hidden">
											<div class="iphone-style" rel="dir-searchinput-geo">&nbsp;</div>
											<?php  } else { ?>
												<input type="checkbox"   id="dir-searchinput-geo" name="geo" class="hidden">
											<div class="iphone-style off" rel="dir-searchinput-geo">&nbsp;</div>
												<?php
													} ?>
										</div>
										<input type="hidden" name="geo-radius" class="slider-slider" value="<?php  echo $_REQUEST['geo-radius']; ?>" id="dir-searchinput-geo-radius" />
									</div>
								</div>
							</div>	
						</div>
					</div>
					<div class="dir-searchinput-settings" id="dir-searchinput-settings">
						<div id="dir-search-advanced">
							<p class="searchbox-title order-by-text search_in_order_p">Results in order</p>
							<div class="search_in_order">
								<select name="search_order" class="form-control" id="search_order">
									<option value="display_name" <?php
										selected($_REQUEST['search_order'], 'display_name'); ?>>Name</option>
									<option disabled="disabled" value="distance" <?php
										selected($_REQUEST['search_order'], 'distance'); ?>>Distance</option>
								</select>
							</div>	
						</div>
					</div>  
				</div>
				<div id="dir-search-button">
					<input type="submit" class="btn btn-primary" value="Search" name="search_btn" id="dir-searchsubmit">
					<input type="button" class="btn btn-primary clear_search" value="Clear Search" name="clear_search_btn" id="clear_search">
					<input type="hidden" value="<?php if(isset($_REQUEST['geo-radius']) && trim($_REQUEST['geo-radius'])!='' && isset($_REQUEST['geo']) && trim($_REQUEST['geo']) == 'on'){ echo '1';}?>" id="check_dist_calc" />
				</div>
						</form>
					</div>
				</div>
			</li>
		</ul>
				</div>
			</div>	
		</li>
	</ul>
	<div>
		
		
		
	
	</div>			
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".clear_search").click(function(){
				window.location.href='<?php echo get_permalink();?>';
			});
		});
		jQuery(function() { 
			var dlat = jQuery("#user_lat").val( ) ;
			var dlong = jQuery("#user_long").val() ;
			var map = jQuery("#mapnew").goMap({
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
				$author_info = get_userAllData($author->ID);
				if (trim($author_info->perma_lat) != '' && trim($author_info->perma_long) != '')
				{
					$icon=plugins_url('assets/images/gmap_marker_blue.png',__FILE__);
					
					$map_arr[] = "{  
							latitude: " . $author_info->perma_lat . ", 
							longitude: " . $author_info->perma_long . ", 
							id: 'map_" . $i . "', 
							icon: '".$icon."',
							html: { content: '<div id=\"iw-container\">' +
						'<div class=\"iw-title\">".get_user_meta($author->ID, 'first_name', true).' '.get_user_meta($author->ID, 'last_name', true)."</div>' +
						'<div class=\"iw-content\">' +
						  '<div class=\"iw-subTitle\">History</div>' +
						  '<img src=\"".get_noone_meta($author_info->ID,'gomap_marker_html')."\" alt=\"".get_user_meta($author->ID, 'first_name', true)."\" width=\"83\">' +
						  '<p>".str_replace("\n","",nl2br(strip_tags(get_the_author_meta('description',$author->ID))))."</p>' +
						  '<div class=\"iw-subTitle\">Contacts</div>' +
						  '<p>E-mail: ".$author_info->user_email."<br/>Website: ".$author_info->user_url."</p>'+
						  '<div class=\"iw-subTitle\"><a href=\"javascript:void(0);\" onClick=\"javascript:info_show(\'".get_user_meta($author->ID, 'first_name', true)." ".get_user_meta($author->ID, 'last_name', true)."\',".$author_info->ID.",450,800);\" class=\"btn btn-primary btn-sm\">VIEW MORE</a></div>'+
						'</div>' +
						'<div class=\"iw-bottom-gradient\"></div>' +
					  '</div>'}
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
			jQuery('.slider-slider').jRange({
					from: 10,
					to: 500,
					step: 1,
					scale: [0,100,200,300,400,500],
					format: '%s Km',
					width: 150,
					showLabels: true,
					onstatechange:function(strr){
						 if(jQuery("#check_dist_calc").val() == "1"){
							jQuery.goMap.removeOverlay('circle', 'riga');
							var dlat = jQuery("#user_lat").val( ) ;
							var dlong = jQuery("#user_long").val() ;
							jQuery.goMap.createCircle({
								id: 'riga',
								latitude: dlat,
								longitude: dlong,
								radius: (strr * 1000)
							});
						  }
					}
				});
			 
			
			
			<?php 
			if(isset($_REQUEST['geo-radius']) && trim($_REQUEST['geo-radius'])!='' && isset($_REQUEST['geo']) && trim($_REQUEST['geo']) == 'on'){?>
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
			<?php
			}?> 
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
				
					$author_info = get_userAllData($author->ID);
 				?>
					<li class="well"> 
						<div class="panel panel-default">
						<div class="panel-heading"><?php echo get_user_meta($author->ID, 'first_name', true).' '.get_user_meta($author->ID, 'last_name', true); ?></div>
						<div class="panel-body">
					 
						 <?php
						   echo '<div class="user_block row"><figure class=" "><img src="' . get_noone_meta($author->ID,'gomap_marker_html') . '" onClick="javascript:info_show(\''.get_user_meta($author->ID, 'first_name', true).' '.get_user_meta($author->ID, 'last_name', true).'\','.$author_info->ID.',450,800);" /></figure></div>';
							
						   echo' <div class="row user_block_btn"><input type="button" class="btn btn-primary btn-block" onClick="javascript:info_show(\''.get_user_meta($author->ID, 'first_name', true).' '.get_user_meta($author->ID, 'last_name', true).'\','.$author_info->ID.',450,800);" value="View Full Info"></div>';

				if (trim($author_info->perma_lat) != '' && trim($author_info->perma_long) != '')
				{
	?>
					<div class="row user_block_btn"><input type="button" class="gmap_button btn btn-primary btn-block" style="margin-top:10px;" id="map_but_<?php echo $i;?>" value="View on Map"></div>
					<script type="text/javascript">
					jQuery(function(){
						
						jQuery("#map_but_<?php echo $i;?>").click(function(){  
						var ulat = jQuery("#user_lat").val() ;
						var ulong = jQuery("#user_long").val() ;
							jQuery.goMap.setMap({latitude:'<?php
								echo $author_info->perma_lat;?>', longitude:'<?php
								echo $author_info->perma_long;?>'
							}); 
							jQuery.goMap.setMap({zoom: 7});
							google.maps.event.trigger(jQuery(jQuery.goMap.mapId).data('map_<?php echo $i;?>'), 'click'); 
							jQuery( window ).scrollTop(0);
						/*if(ulat!='' && ulong!=''){
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
						}*/
						});
					});
					</script>
				<?php			   
				}  				   
				echo'<div class="row icons text-center" >';
				if ( is_user_logged_in() ) {
					echo '<a href="mailto:'.$author_info->user_email.'" class="email"></a>';
				}
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
