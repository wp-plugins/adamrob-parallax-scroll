/********************************
** adamrob.co.uk - 9JAN2014
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

	//retrieve variables
	var parallaxoptions = parallax_script_options;
	
	//Alter height of parallax container
	$('#'+parallaxoptions.parallaxcontainerid).css("height", $('#'+parallaxoptions.parallaxdivid).css("height") );

	//Alter the CSS properties of the parallax
	$('#'+parallaxoptions.parallaxdivid).css("position", 'absolute' );
	$('#'+parallaxoptions.parallaxdivid).css("left", '0' );
	$('#'+parallaxoptions.parallaxdivid).css("width", '100%' );
	
	
});