/**
 * Created by stefan on 31/12/15.
 */

jQuery('a.local-scroll').on('click', function(){
        scrollToElement( jQuery(this).attr('href'));
    }
);


function scrollToElement(aid){
    var element = jQuery(aid);
    element.removeClass('bounce-up');
    jQuery('html,body').animate({scrollTop: element.offset().top-20},'slow');
    element.addClass('bounce-up');
}

var $animation_elements = jQuery('.bounce-up');
var $window = jQuery(window);

$window.on('scroll resize', check_if_in_view);

$window.trigger('scroll');

function check_if_in_view() {
    var window_height = $window.height();
    var window_top_position = $window.scrollTop();
    var window_bottom_position = (window_top_position + window_height);

    jQuery.each($animation_elements, function() {
        var $element = jQuery(this);
        var element_height = $element.outerHeight();
        var element_top_position = $element.offset().top;
        var element_bottom_position = (element_top_position + element_height);

        //check to see if this current container is within viewport
        if ((element_bottom_position >= window_top_position) &&
            (element_top_position <= window_bottom_position)) {
            $element.addClass('in-view');
        } else {
            $element.removeClass('in-view');
        }
    });
}


jQuery(document).ready(function () {

    // you want to enable the pointer events only on click;
    $gmap = jQuery('.wpb_gmaps_widget.vc_map_responsive .wpb_map_wraper');

    $gmap.on('click', function () {
        $gmap.removeClass('scrolloff'); // set the pointer events true on click
    });


    $gmap.addClass('scrolloff'); // set the pointer events to none on doc ready

    // you want to disable pointer events when the mouse leave the canvas area;

    $gmap.mouseleave(function () {
        $gmap.addClass('scrolloff'); // set the pointer events to none when mouse leaves the map area
    });
});