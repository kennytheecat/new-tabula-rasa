/* 
 * Mmenu settings
 */

jQuery(document).ready(function($){

    $(".nav-menu").mmenu({
       // options
    }, {
       // configuration
       clone: true
    });

	  $(".menu-toggle").click(function() {
	  	$(".nav-menu").trigger("open.mm");
	  });

});