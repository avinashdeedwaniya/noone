<?php
function noone_grid_searching()
{
    global $wpdb;
    
    $no=20;// total no of users to display

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if($paged==1){
      $offset=0;  
    }else {
       $offset= ($paged-1)*$no;
    }
     
$args = array (
	'distinct' => true,
    'role' => 'subscriber',
    'order' => 'ASC',
    'orderby' => 'display_name',
    //'search' => '*'.esc_attr( $search_term ).'*',
     'number' => $no, 
     'offset' => $offset,
    'meta_query' => array(
        'relation' => 'AND',
     
    )
);

if (isset($_REQUEST['search_name']) && trim($_REQUEST['search_name']) != '')
    {
		$name_arr=explode(" ",$_REQUEST['search_name']);
		
		if(count($name_arr) > 1){
		$args['meta_query'][]=array(
			'relation' => 'OR',			
			array(
				'key'     => 'first_name',
				'value'   => $name_arr[0],
				'compare' => 'LIKE'
			),
			array(
				'key'     => 'last_name',
				'value'   => $name_arr[0],
				'compare' => 'LIKE'
			),
			array(
				'key'     => 'first_name',
				'value'   => end($name_arr),
				'compare' => 'LIKE'
			),
			array(
				'key'     => 'last_name',
				'value'   => end($name_arr),
				'compare' => 'LIKE'
			)
        );
	}else{
		$args['meta_query'][]=array(
			'relation' => 'OR',			
			array(
				'key'     => 'first_name',
				'value'   => $name_arr[0],
				'compare' => 'LIKE'
			),
			array(
				'key'     => 'last_name',
				'value'   => end($name_arr),
				'compare' => 'LIKE'
			)
        );
		}
 } 
 
 if (isset($_REQUEST['search_city']) && trim($_REQUEST['search_city']) != '')
    {
		$args['meta_query'][]=array(
            'key'     => 'city',
            'value'   => strtolower($_REQUEST['search_city']),
            'compare' => 'LIKE'
        );
        
    } 
    
    if (isset($_REQUEST['search_state']) && trim($_REQUEST['search_state']) != '')
    {
        $args['meta_query'][]=array(
            'key'     => 'state',
            'value'   => strtolower($_REQUEST['search_state']),
            'compare' => 'LIKE'
        );         
    }
      
       if (isset($_REQUEST['search_sector']) && trim($_REQUEST['search_sector']) != '')
    {
        $args['meta_query'][]=array(
			'relation' => 'OR',
			array(
            'key'     => 'service_type',
            'value'   => strtolower($_REQUEST['search_sector']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'retire_type',
            'value'   => strtolower($_REQUEST['search_sector']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'social_type',
            'value'   => strtolower($_REQUEST['search_sector']),
            'compare' => 'LIKE'
        )  
        );
        
    }
        
         if (isset($_REQUEST['search_occp_city']) && trim($_REQUEST['search_occp_city']) != '')
    {
        $args['meta_query'][]=array(
			'relation' => 'OR',
			array(
            'key'     => 'self_city',
            'value'   => strtolower($_REQUEST['search_occp_city']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'service_city',
            'value'   => strtolower($_REQUEST['search_occp_city']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'retire_city',
            'value'   => strtolower($_REQUEST['search_occp_city']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'social_city',
            'value'   => strtolower($_REQUEST['search_occp_city']),
            'compare' => 'LIKE'
        )
        );
    }
    if (isset($_REQUEST['search_occp_state']) && trim($_REQUEST['search_occp_state']) != '')
    {
        $args['meta_query'][]=array(
			'relation' => 'OR',
			array(
            'key'     => 'self_state',
            'value'   => strtolower($_REQUEST['search_occp_state']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'service_state',
            'value'   => strtolower($_REQUEST['search_occp_state']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'retire_state',
            'value'   => strtolower($_REQUEST['search_occp_state']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'social_state',
            'value'   => strtolower($_REQUEST['search_occp_state']),
            'compare' => 'LIKE'
        )
        );
      
    }
    
     if (isset($_REQUEST['search_occp_type']) && trim($_REQUEST['search_occp_type']) != '')
    {
        $args['meta_query'][]=array(
			'relation' => 'OR',
		array(
            'key'     => 'self_title',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),
        array(
            'key'     => 'service_title',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),
        array(
            'key'     => 'retire_title',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),
        array(
            'key'     => 'social_title',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),
        array(
            'key'     => 'self_service',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'self_info',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'service_post_name',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'service_info',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'retire_post_name',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'retire_info',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'social_work_as',
            'value'   => strtolower($_REQUEST['search_occp_type']),
            'compare' => 'LIKE'
        ),array(
            'key'     => 'social_info',
            'value'   => strtolower($_REQUEST['search_occp_type']),
           'compare' => 'LIKE'
        )
        );
    }
    
// Create the WP_User_Query object 
$wp_user_query = new WP_User_Query($args);
// Get the results
$authors = $wp_user_query->get_results();


 
    echo '<div class="modal_body_wrap"></div><div class="author-entry">';
?>
<script src="<?php echo plugins_url('assets/js/jquery.validate.js', __FILE__)?>"></script> 
 
   <div data-interactive="yes" class="map-search no-map" id="directory-search">
					<div class="wrapper">
						<div class="dir-searchform well" id="dir-search-form">
							<form class="form-horizontal"  method="get" action="<?php echo get_permalink();?>"
							  >
								  <input type="hidden" name="user_lat" value="<?php echo $_REQUEST['user_lat'];?>" id="user_lat">
								  <input type="hidden" id="user_long" value="<?php echo $_REQUEST['user_long'];?>" name="user_long">
								
								<div class="clearfix">
								<div id="dir-search-inputs" >
									<p class="searchbox-title">Search By Person</p>
									<div id="dir-holder">
										<div class="dir-holder-wrap">
											<input type="text" name="search_name" placeholder="Full Name" 
											id="search_name"  class="dir-searchinput form-control" value="<?php echo $_REQUEST['search_name'];?>" />
										 

										 <input type="text" placeholder="City" id="search_city" 
										class="ui-autocomplete-input form-control"   name="search_city" value="<?php
		echo $_REQUEST['search_city'];
	?>" />
										<input type="text" class="ui-autocomplete-input state form-control" placeholder="State" autocomplete="off" id="search_state" name="search_state" value="<?php
		echo $_REQUEST['search_state'];
	?>" /> <div class="skey"></div>
								<div id="dir-search-button" class="hidden-xs">
								 
									<input type="submit" class="dir-searchsubmit btn btn-primary" value="Search" name="search_btn" id="dir-searchsubmit">
								</div>
										</div>
									</div>
								</div>
								<div class="dir-searchinput-settings"  id="dir-searchinput-settings">
											<div id="dir-search-advanced">
												<p class="searchbox-title text">Search by Profession</p>

												<div class="search-slider-geo">
													<select name="search_sector" class="form-control">
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
				 <input type="text" name="search_occp_type" class="form-control"  placeholder="Job Post or Info" value="<?php
		echo $_REQUEST['search_occp_type'];
	?>" /> 

				<input type="text" name="search_occp_city" class="form-control" placeholder="Job City" value="<?php
		echo $_REQUEST['search_occp_city'];
	?>" /> 

				<input type="text" name="search_occp_state" class="form-control" placeholder="Job State" value="<?php
		echo $_REQUEST['search_occp_state'];
	?>" /> 
												</div>
											</div>
											<div id="dir-search-button" class="visible-xs">
								 
									<input type="submit" class="dir-searchsubmit btn btn-primary" value="Search" name="search_btn" id="dir-searchsubmit">
								</div>
										</div> 
										</div>
								 
								
								<input type="hidden" value="yes" name="dir-search">
								
							</form>
						</div>
					</div>
				</div> 
<?php
if (!empty($authors)) {
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
				
				
				<?php if (isset($_REQUEST['search_sector']) && trim($_REQUEST['search_sector']) != '')
				{?>
				<div class="col-sm-3"><?php echo ucfirst($_REQUEST['search_sector']);?> </div>
				<?php }
				if (isset($_REQUEST['search_occp_type']) && trim($_REQUEST['search_occp_type']) != '')
					{?>
				<div class="col-sm-3">in <?php echo $_REQUEST['search_occp_type'];?> </div>
				<?php }?>
				<?php if (isset($_REQUEST['search_occp_city']) && trim($_REQUEST['search_occp_city']) != '')
					{?>
				<div class="col-sm-3">in <?php echo $_REQUEST['search_occp_city'];?> </div>
				<?php }?>
				<?php if (isset($_REQUEST['search_occp_state']) && trim($_REQUEST['search_occp_state']) != '')
					{?>
				<div class="col-sm-3">in <?php echo $_REQUEST['search_occp_state'];?> </div>
				<?php }?>
				 
			</div>
        </div>
        <ul class="ul_user" > 
 <?php $i = 0;
        foreach ($authors as $author)
        {
            $i++;
            $author_info = get_userdata($author->ID);
?>
    
	   <li class="well"> <div class="panel panel-default">
                <div class="panel-heading"><?php echo $author_info->first_name.' '.$author_info->last_name; ?></div>
                <div class="panel-body">
                  <?php
					echo '<div class="user_block row"><figure class=" "><img src="' . get_noone_meta($author->ID,'gomap_marker_html') . '" onClick="javascript:info_show(\''.$author_info->first_name.' '.$author_info->last_name.'\','.$author_info->ID.',450,800);" class="img-circle" /></figure></div>';
				   
				   echo' <div class="row"><input type="button" class="btn btn-primary btn-block" onClick="javascript:info_show(\''.$author_info->first_name.' '.$author_info->last_name.'\','.$author_info->ID.',450,800);" value="View Full Info"></div>';
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
         
                
            </li>
        <?php
        } //$fivesdrafts as $author
       ?>
        </ul>
        <div class="modal">
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
				<?php
				$query_string = $_SERVER['QUERY_STRING'];
				$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('paged', $query_string) . '%_%';
 ?>
                 <ul class="pagination">
                    <?php // grab the current query parameters


			$total_user = $wp_user_query->total_users;  
            $total_pages=ceil($total_user/$no);

              echo strip_tags(paginate_links(array(  
                  'base' => $base, 
                  'format' => '&paged=%#%',  
                  'current' => $paged,  
                  'total' => $total_pages,  
                  'prev_text' => '&laquo;',  
                  'next_text' => '&raquo;',
                  'type'     => 'list',
                )),'<li><a><span>');  
                ?>
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
add_shortcode('noone_grid_searching', 'noone_grid_searching'); 
?>
