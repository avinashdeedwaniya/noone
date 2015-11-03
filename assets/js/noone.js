function sgetval(id, value) {
        jQuery('#' + id).parents(".skey").siblings('.state').val(value)
        jQuery('.skey').html('');
    }
jQuery(document).ready(function() {
	 var _window = jQuery(window).width();
	 var _wHeight = jQuery(window).height();
	
    function getLocation() {
        if (navigator.geolocation) {
            jQuery('#search_order option[value=distance]').prop('disabled', false);
            navigator.geolocation.getCurrentPosition(showPosition);
            
        } else {
            jQuery('#search_order option[value=distance]').prop('disabled', true);
            jQuery("#check_dist_calc").attr("value","0");
            jQuery("#dir-searchinput-geo").attr("checked", "");
            jQuery(".iphone-style").addClass("off");
            jQuery("#dir-searchinput-geo-radius").val('');
            
        }
    }
    
    function showPosition(position) {
        jQuery.goMap.removeMarker('position_marker');
        jQuery("#user_lat").val(position.coords.latitude);
        jQuery("#user_long").val(position.coords.longitude);
        jQuery.goMap.setMap({
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
        });
        jQuery.goMap.setMap({
            zoom: 8
        });

        var radius_val = jQuery("#dir-searchinput-geo-radius").val();
        if (!radius_val) {
            radius_val = 10;
        }

        jQuery.goMap.removeOverlay('circle', 'riga');
        jQuery.goMap.createMarker({
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            id: 'position_marker',
            title: 'You are here.',
            draggable: true,
            icon: 'http://172.16.1.219/wptest/wp-content/plugins/noone/assets/images/map-marker.png',
            html: {
                content: 'You are here.'
            }
        });

        jQuery.goMap.createCircle({
            id: 'riga',
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            radius: (radius_val * 1000)
        });
         
        jQuery("#check_dist_calc").attr("value","1");

        jQuery.goMap.createListener({
            type: 'marker',
            marker: 'position_marker'
        }, 'dragend', function() {
            jQuery.goMap.removeOverlay('circle', 'riga');
            jQuery("#user_lat").val(this.getPosition().lat());
            jQuery("#user_long").val(this.getPosition().lng());
            var radius_val = jQuery("#dir-searchinput-geo-radius").val();
            jQuery.goMap.createCircle({
                id: 'riga',
                latitude: this.getPosition().lat(),
                longitude: this.getPosition().lng(),
                radius: (radius_val * 1000)
            });
        });



        if (jQuery(".iphone-style").hasClass("off")) {
            jQuery("#dir-searchinput-geo").attr("checked", true);
            jQuery(".iphone-style").removeClass("off");
        } else {
            jQuery("#dir-searchinput-geo").attr("checked", false);
            jQuery(".iphone-style").addClass("off");

        }
    }

    jQuery(".iphone-style").on("click", function() {
 
        if (jQuery(this).hasClass("off")) {
			 
            getLocation();
            //jQuery("#dir-searchinput-geo").attr("checked","checked");
            //jQuery(this).removeClass("off");
        } else {
			 
            jQuery("#dir-searchinput-geo").attr("checked", false);
            jQuery(this).addClass("off");
            jQuery("#user_lat").val('');
            jQuery("#user_long").val('');
			jQuery.goMap.showHideMarker('position_marker');
            jQuery.goMap.removeOverlay('circle', 'riga');

        }
    });

    jQuery(".state").keyup(function() {
        var this_div = jQuery(this);
        var sid = jQuery(this).val();
        var ajaxurl = nooneobject.nooneajaxurl;
        var data = {
            action: "state_action_front",
            state: sid
        };
        jQuery.post(ajaxurl, data, function(response) {
            this_div.siblings(".skey").html(response);
        });
    });
	
	function set_map_searchbar_height(){
		var _window = jQuery(window).width();
		var _wHeight = (jQuery(window).height());
		var _mapSearchHeight = jQuery("#map-search-form").outerHeight(true);
		jQuery("#mapnew").css("height", (_wHeight-33));		 
		if(_window > 767){			
			
			if(jQuery("#mapnew").height() < _mapSearchHeight){
				jQuery("#map-search-form").css("top", '17px');
				jQuery("#map-search-form").css("height", (jQuery("#mapnew").height()));
				 
				jQuery("#map-search-form").mCustomScrollbar({
					setHeight:eval(_wHeight),
					theme:"dark-3"
				});	
			}
			else{
				jQuery("#map-search-form").css("height", 'auto');
				jQuery("#map-search-form").css("top", (jQuery("#mapnew").height() - (_mapSearchHeight)));
				jQuery("#map-search-form").mCustomScrollbar("destroy");
			 
			}
		}
		else{
			jQuery("#map-search-form").css("height", 'auto');
			jQuery("#map-search-form").css("top", '0px');
			jQuery("#map-search-form").css("overflow", '');
		}
	}
	 
    setTimeout(function() {
        equalizer();
        set_map_searchbar_height();
    }, 300);

    jQuery(window).resize(function() {
        setTimeout(function() {
            equalizer();
            //set_map_searchbar_height();
        }, 300);

    });
    jQuery(window).on("orientationchange", function(event) {
        setTimeout(function() {
            equalizer();
            set_map_searchbar_height();
        }, 300);

    });
    jQuery("ul.pagination li").removeClass("active");
    jQuery("ul.pagination li span").each(function() {
        if (jQuery(this).hasClass("current")) {
            jQuery(this).parent("li").addClass("active");
        }

    });

    
});
function equalizer() {
        var highestBox = 0;
        jQuery('.user_block').each(function() {
            jQuery(this).css("height", "");
            if (jQuery(this).height() > highestBox)
                highestBox = jQuery(this).height();
        });
        jQuery('.user_block').height(highestBox);

    }

    function info_show(name, uid, height, width) {
        var _window = jQuery(window).width();
        var ajaxurl = nooneobject.nooneajaxurl;
        var data = {
            action: "getUserDetails",
            user_id: uid
        };
        jQuery.post(ajaxurl, data, function(response) {
            jQuery(".modal-body").html(response);
            jQuery("body").addClass("modal_wrap");
            if (_window > 767) {
                jQuery(".tab-pane").mCustomScrollbar({
                    setHeight: 324,
                    theme: "dark-3"
                });
            }
        });
        jQuery("h4.modal-title").html(name);
        jQuery(".modal").show();
    }

    function info_remove() {
        jQuery(".modal").hide();
        jQuery("h4.modal-title").html('');
        jQuery(".modal-body").html('');
        jQuery("body").removeClass("modal_wrap");
    }
