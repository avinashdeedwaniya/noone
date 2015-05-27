function sgetval(id,value) { 
                       jQuery('#'+id).parents(".skey").siblings('.state').val(value)
                        jQuery('.skey').html('');
        }
        jQuery(document).ready(function(){
			 
			function getLocation() {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(showPosition);
				}  
				else{
					jQuery("#dir-searchinput-geo").attr("checked","");
					jQuery(".iphone-style").addClass("off");

						
				 }
			}
		 

			function showPosition(position) {

				jQuery("#user_lat").val(position.coords.latitude) ;
				 jQuery("#user_long").val(position.coords.longitude) ;	
				 jQuery.goMap.setMap({latitude:position.coords.latitude, longitude:position.coords.longitude}); 
				 jQuery.goMap.setMap({zoom: 8});
				 /*jQuery("#mapnew").goMap({ 
					overlays: [{
						type: 'circle',
						id: 'c',
						latitude: position.coords.latitude, 
						longitude: position.coords.longitude,
						radius: 100000
					}]
				 });*/
				 jQuery.goMap.createMarker({
					latitude: position.coords.latitude,
					longitude: position.coords.longitude,
					icon: 'http://localhost/wptest/wp-content/plugins/noone/assets/images/map-marker.png',
					html: { content: 'You are here.'}
				});
				 jQuery.goMap.createCircle({
					id: 'riga',
					latitude: position.coords.latitude,
					longitude: position.coords.longitude,
					radius: 100000
				});
				if(jQuery(".iphone-style").hasClass("off")){
					jQuery("#dir-searchinput-geo").attr("checked",true);
					jQuery(".iphone-style").removeClass("off");
				 }
				 else{
					jQuery("#dir-searchinput-geo").attr("checked",false);
					jQuery(".iphone-style").addClass("off");

				 }
			}
			jQuery(".iphone-style").on("click",function(){
				 if(jQuery(this).hasClass("off")){
					getLocation();
					//jQuery("#dir-searchinput-geo").attr("checked","checked");
					//jQuery(this).removeClass("off");
				 }
				 else{
					jQuery("#dir-searchinput-geo").attr("checked",false);
					jQuery(this).addClass("off");
					jQuery("#user_lat").val('') ;
				 	jQuery("#user_long").val('') ;
				 }
					 
					
				 
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
            jQuery("#TB_overlay").hide();
			jQuery(".TB_window").hide();
			var map_height = jQuery( window ).height();
			jQuery("#mapnew").css("height",(map_height-20));
			setTimeout(function(){ equalizer() }, 3000);
			
			jQuery( window ).resize(function() {
			 	setTimeout(function(){ equalizer() }, 3000);
			});
			jQuery( window ).on( "orientationchange", function( event ) {
				setTimeout(function(){ equalizer() }, 3000);
			});
			
			 
			
        });
 
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
				var ajaxurl=nooneobject.nooneajaxurl; 
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
