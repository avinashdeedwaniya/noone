<?php	
	function noone_grid_searching()
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
	 <div class="container no-map">
		<script src="<?php echo plugins_url('assets/js/jquery.validate.js', __FILE__)?>"></script> 
		 
					 
						  <div class="panel panel-default search_frm">
							 <div class="panel-body">
								<form   method="get" action="<?php  echo get_permalink(); ?>">
								   
								   <div id="form_fileds" class="col-xs-12">
									  <!--   class="mCustomScrollbar" -->
									  <div class="row show-grid">
										<p class="search_personal_p active text-left">Search By Person <i class="glyphicon glyphicon-circle-arrow-up"></i> </p>
										<div class="search_personal row">
										<div class="col-xs-12 col-sm-4 form-group">
										 <input type="text" name="search_name" placeholder="Full Name" id="search_name"  class="dir-searchinput form-control" value="<?php  echo $_REQUEST['search_name']; ?>" />
										 </div>
										 <div class="col-xs-12 col-sm-4 form-group">
										 <input type="text" placeholder="City" id="search_city" class="ui-autocomplete-input form-control"   name="search_city" value="<?php	echo $_REQUEST['search_city']; ?>" /> 
										 </div>
										 <div class="col-xs-12 col-sm-4 form-group">
										 <input type="text" class="state form-control" placeholder="State" autocomplete="off" id="search_state" name="search_state" value="<?php echo $_REQUEST['search_state']; ?>" /> 
										 <div class="skey"></div>
										 </div>
										 </div>
									  </div>
									  <div class="row show-grid">
										 <p class=" search_profession_p text-left">Search by Profession <i class="glyphicon glyphicon-circle-arrow-down"></i> </p>
										 <div class="search_profession row">
										 <div class="col-sm-3 form-group">
											<select name="search_sector" class="form-control">
											   <option value="" <?php
												  selected($_REQUEST['search_sector'], ''); ?>>-- Job Sector --</option>
											   <option value="govt" <?php
												  selected($_REQUEST['search_sector'], 'govt'); ?>>Government</option>
											   <option value="private" <?php
												  selected($_REQUEST['search_sector'], 'private'); ?>>Private</option>
											</select>
											</div>
											<div class="col-sm-3 form-group">
											<input type="text" name="search_occp_type" class="form-control" placeholder="Job Post or Info" value="<?php
											   echo $_REQUEST['search_occp_type']; ?>" />
											   </div>
											<div class="col-sm-3 form-group">
											<input type="text" name="search_occp_city" class="form-control" placeholder="Job City" value="<?php
											   echo $_REQUEST['search_occp_city']; ?>" /> 
											   </div>
											<div class="col-sm-3 form-group">
											<input type="text" name="search_occp_state" class="form-control state" placeholder="Job State" value="<?php
											   echo $_REQUEST['search_occp_state']; ?>" /> 
											<div class="skey"></div>
											</div>
											 </div>
									  </div>
									   
									   
								   </div>
								   <div class="row show-grid">
									   <div class="col-xs-12">
									   <input type="submit" class="btn btn-primary " value="Search" name="search_btn" id="dir-searchsubmit">
									   <input type="button" class="btn btn-primary clear_search" value="Clear Search" name="clear_search_btn"> 
									   </div>
									</div>
								</form>
							 </div>
						  </div>
					    
				  
	 		
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery(".clear_search").click(function(){
				window.location.href='<?php echo get_permalink();?>';
			});
		});		 
	</script>	 
	<?php
	if ($fivesdrafts)
		{
			?>
			  <ol class="breadcrumb"><li><a href="<?php echo home_url();?>">Home</a></li><li class="active">Noone Search</li></ol>
			  
				 
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
						   echo '<div class="user_block row"><figure class=" "><img src="' . get_noone_meta($author->ID,'noone_user_search') . '" onClick="javascript:info_show(\''.get_user_meta($author->ID, 'first_name', true).' '.get_user_meta($author->ID, 'last_name', true).'\','.$author->ID.',450,800);" width="150" /></figure></div>';
							
						   echo' <div class="row user_block_btn"><input type="button" class="btn btn-primary btn-block" onClick="javascript:info_show(\''.get_user_meta($author->ID, 'first_name', true).' '.get_user_meta($author->ID, 'last_name', true).'\','.$author->ID.',450,800);" value="View Full Info"></div>';

				   				   
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
		 
	   <div style="margin-top:20px;">
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
	    
			<div style="margin-top:20px;">
				<div class="col-sm-12">Apologies, but no results were found for the request. </div>
			</div> 
		 
		<?php
		}
		?>
	</div>
	<?php    
	}
	add_shortcode('noone_grid_searching', 'noone_grid_searching'); 
	?>
