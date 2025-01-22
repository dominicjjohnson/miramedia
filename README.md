# miramedia
Random bits of code that might help

ISSUE - No X (Twitter) Icon in Visual Composer - Add this code to your style.css page for your theme - it needs to be before the Visual Composer CSS

.vcv-ui-icon-socialicons-twitter:before {
	font-family: "Font Awesome 6 Brands";
	content: "\e61b" !important;
	font-size: 31px !important;
	top: 45% !important;
}

If it doesn't work, look at your version of fontawesome - you need version:

function enqueue_font_awesome() {
	 wp_enqueue_style(
		 'my-font-awesome',
		 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
		 array(),
		 null,
		 'all'
	 );
 }
 
 add_action('wp_enqueue_scripts', 'enqueue_font_awesome', 1000);

 You might need to stop loading other FontAwsome CSS files, here is the code I used:

 function dequeue_font_awesome_styles() {
	 // Deregister specific Font Awesome styles
	 wp_dequeue_style('font-awesome-official-v4shim');
	 wp_dequeue_style('flipbox_builder_flip_font_osm');
	 wp_dequeue_style('bfa-font-awesome');
	 wp_dequeue_style('font-awesome-official');
 
	 // Also deregister to make sure they don't load
	 wp_deregister_style('font-awesome-official-v4shim');
	 wp_deregister_style('flipbox_builder_flip_font_osm');
	 wp_deregister_style('bfa-font-awesome');
	 wp_deregister_style('font-awesome-official');
 }
 
 add_action('wp_enqueue_scripts', 'dequeue_font_awesome_styles', 999);

 If you need to find the names of the css files you've loaded, this code will display that:

  add_action('wp_print_styles', 'print_all_styles');
 function print_all_styles() {
	 global $wp_styles;
	 echo '<pre>';
	 print_r($wp_styles->queue);
	 echo '</pre>';
 }

 I hope this helps, thanks
