/**
 * Created by stefan on 31/12/15.
 */

jQuery('a.local-scroll').on('click', function(){
        scrollToElement( jQuery(this).attr('href'));
    }
);


function scrollToElement(aid){
    var element = jQuery(aid);
    jQuery('html,body').animate({scrollTop: element.offset().top},'slow');
}