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
                var ajaxurl=nooneobject.nooneajaxurl; 
                var data ={ action: "state_action_front",  state:sid    };
                jQuery.post(ajaxurl, data, function (response){
                    this_div.siblings(".skey").html(response);
                });
            });
            
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
                    jQuery(".modal-body").html(response);
                    
                });
                jQuery("h4.modal-title").html(name);
                jQuery(".modal").show();
                
 
                
			}
			function info_remove(){
 
                jQuery(".modal").hide();
                jQuery("h4.modal-title").html('');
                jQuery(".modal-body").html('');
			}
