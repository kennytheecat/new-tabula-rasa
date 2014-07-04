/* 
 * Toggles search on and off
 */
jQuery(document).ready(function($){
	$(".search-mobile").click(function(){
		$("#search-container").slideToggle('slow', function(){
			$('.search-mobile').toggleClass('active');
		});
		// Optional return false to avoid the page "jumping" when clicked
		return false;
	});

	$(".search-not-mobile").click(function(){
		$("#search-container").slideToggle('slow', function(){
			$('.search-not-mobile').toggleClass('active');
		});
		// Optional return false to avoid the page "jumping" when clicked
		return false;
	});	
		
});