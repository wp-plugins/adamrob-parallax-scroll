/********************************
** adamrob.co.uk - 11NOV2014
** Parallax Scroll Wordpress Plugin
**
** For Help and Support please visit www.adamrob.co.uk
**
** fullwidth.js
** Provides full width functionality regardless of
** parent divs by re-sizing the container div to the
** same height as the absolute positioned parallax
********************************/

jQuery(document).ready(function($){
	
	$('.parallax-window-container').css("height", $('.parallax-window').css("height") );
	
	
});