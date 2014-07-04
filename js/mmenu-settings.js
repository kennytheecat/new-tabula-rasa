/* 
 * Mmenu settings
 */

jQuery(document).ready(function($){

    $(".mmenu-toggle").mmenu({
       // options
    }, {
       // configuration
       clone: true
    });

	  $(".mobile-menu").click(function() {
	  	$(".mmenu-toggle").trigger("open.mm");
	  });

});