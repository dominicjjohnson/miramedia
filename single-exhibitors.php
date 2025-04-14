<?php get_header();
	
// SSP-600 - Add a modal window to the category list when the number of categories > 10
?>
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}


#appointments-response {
    color:red !important;
    font-weight:bold !important; 
}

</style>	
	
<?php
	
$page_layout = of_get_option('page_layout');
$dynamic_layout = of_get_option('dynamic_layout');
if(empty($page_layout)) {
	$page_layout = 'default';
}

/**
 * Detect plugin. For use on Front End only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// check for plugin using plugin name
if ( is_plugin_active( 'cw-plugin-enhanced-exhibitor/cw-plugin-enhanced-exhibitor.php' ) ) {
  //plugin is activated
    $enhanced_active = 'enhanced-exhibitor-enabled';
} 
else {
	$enhanced_active = "";
}
?>

<?php
	// TJW SSP-406 
	$hide_ex_enhanced = of_get_option('hide-ex-enhanced');
	$hide_appointment_system = of_get_option('hide-appointments');
?>

<div id="content" class="<?php echo $page_layout; ?><?php if($page_layout == 'full-width') { echo ' ' . $dynamic_layout; } ?>">
	<div id="inner-content" class="wrap cf">
		<main id="main">
			<div class="individual-exhibitor <?php echo $enhanced_active; ?>">
<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>
 <?php
	// TJW MSTS-46 later code destroys the value which we need to do the exhibitor shortcode further down
	$post_name =	$post->post_name;
	 	
	$contact_name = get_post_meta($post->ID, 'contact_name', true);
	$email      = get_post_meta($post->ID, 'public_email', true);
	$website    = get_post_meta($post->ID, 'website_url', true);
	$telephone  = get_post_meta($post->ID, 'public_phone', true);
	$linkedin   = get_post_meta($post->ID, 'linkedin_url', true);
	$twitter    = get_post_meta($post->ID, 'twitter_url', true);
	$facebook   = get_post_meta($post->ID, 'facebook_url', true);
	$google     = get_post_meta($post->ID, 'google_url', true);
	$instagram  = get_post_meta($post->ID, 'instagram_url', true);
	$pinterest  = get_post_meta($post->ID, 'pinterest_url', true);
	$youtube    = get_post_meta($post->ID, 'youtube_url', true);
	$address    = get_post_meta($post->ID, 'company_address', true);
	$association = get_post_meta($post->ID, 'ex_association', true);
	$stand      = rwmb_meta('stand_number', $post->ID);
	
	// SSP-600. Reduce the number of categoies to 5. The display a [more] link. 	
	$category   = get_the_term_list( $post->ID, 'category', '', ', ', '' ) ;
	$category   = strip_tags($category);
	
	// Split the full category list into an Array. Then get just 5 categories and put them in a new string.
	$categoryArray = explode(',', $category);
	
	// Need to work out what is a unique category name and remove the non-uniques
	// There is a space at the very first value and so we need to add one.
	
	$categoryArray[0] = " ".$categoryArray[0];
	
	$categoryArraySingles = array();
	foreach($categoryArray as $key => $value) {
		if ( !in_array($value, $categoryArraySingles)) {
			$categoryArraySingles[] = $value;
		}
	}

	if (isset($categoryArraySingles)) {
		if ( !in_array($value, $categoryArraySingles)) {
			$categoryArraySingles[] = $value;
		}
	}

	$cats_short;
	 
	$max_cats = 10;
	if (isset($categoryArraySingles)) {
		if (sizeof($categoryArraySingles) < $max_cats) {
			$max_cats = sizeof($categoryArraySingles);	
		}
	}
	
	if (!isset($cats_short)) {
		$cats_short = "";
	}
	
	for ($x = 0; $x <= $max_cats; $x++) {
		if (isset($categoryArraySingles)) {
			if (isset($categoryArraySingles[$x])) { // Check if the index exists
				$cats_short .= $categoryArraySingles[$x] . ", ";
			}
		}
	} 

	// Remove the last comma, add a link to more if there are more than 6 categories - ie. there are more of them. 
	if (IsSet($cats_short)) { $cats_short = rtrim($cats_short, ", "); }
	if (isset($categoryArraySingles)) {
		if (sizeof($categoryArraySingles) > 10){
			$cats_short .= ' <a href="#" id="myBtn">[more]</a>';
		}
	}
	
	$enhanced = get_post_meta(get_the_id(), 'featured_exhibitor', true);
	$halls       = rwmb_meta('hall_selection', $post->ID ) ;
	$building_name = get_post_meta($post->ID, 'building_name', true);
	$address_line_1 = get_post_meta($post->ID, 'address_line_one', true);
	$address_line_2 = get_post_meta($post->ID, 'address_line_two', true);
	$town_city = get_post_meta($post->ID, 'town_city', true);
	$county = get_post_meta($post->ID, 'county', true);
	$postcode = get_post_meta($post->ID, 'postcode', true);
	$country = rwmb_the_value('country', '', $post->ID, false);
	if($country == 'uae' || $country == 'United Arab Emirates') {
		$town_city = rwmb_the_value('town_city_uae', '', $post->ID, false);
	}
	if($country == 'Please Select') {
		$country = '';
	}
	$new_address = array($building_name, $address_line_1, $address_line_2, $town_city, $county, $postcode, $country);
	
	$display_categories = of_get_option('exhibitor_no_categories');
	if ( $display_categories == "" ) { $display_categories = 0; } // USed in switch later
	
    $enhanced = get_post_meta($post->ID, 'featured_exhibitor', true);
    if ($enhanced == 1) {
        $enhanced_exhibitor = 'enhanced_exhibitor';
        if ($hide_ex_enhanced){
	        $enhanced_exhibitor_detail = 'not_enhanced_exhibitor';
        }
        else {
	        $enhanced_exhibitor_detail = 'enhanced_exhibitor';
        }
        
    } else {
        $enhanced_exhibitor = 'not_enhanced_exhibitor';
        $enhanced_exhibitor_detail = 'not_enhanced_exhibitor';
    }
 ?>
 
<?php 
	/* There is an error here where $enhanced_exhibitor_detail returns fals when it shouldn't.
	
	echo $enhanced_exhibitor_detail; ?>
	*/
?>
 
 <!-- Company name and logo -->
 <div class="exhibitor-left">
	 <h1 class="exhibitor-name"><?php the_title(); ?></h1>
	<?php
	$images = rwmb_meta('profile_logo', array('size' => 'sponsor_detail_photo'));
		if( has_post_thumbnail() ) {
			$exhibitor_logo_src  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'sponsor_detail_photo', false, '' );
			echo '<div id="ex-logo" class="' . $enhanced_exhibitor . '">
				<img src="' . $exhibitor_logo_src[0] . '" />
				</div>';
		} elseif ( !empty($images) ) {
			foreach ( $images as $image ) {
				echo '<div id="ex-logo" class="' . $enhanced_exhibitor . '">
					<img src="' . $image['url'] . '" />
					</div>';
			}
		} else {}
	?>
 </div>
	 
	 
 <div class="exhibitor-details">

	 <!-- Associations -->
	 <?php
		if(!empty($association)){    
			echo '<p><span class="details-heading"><strong>Association: </strong></span>'.$association.'</p>';
		}
	 ?>



	 <!-- Stand and Categories -->
	 <?php
		echo '<p>';
		if(!empty($halls)){    
			echo '<span class="details-heading"><strong>Hall(s): </strong></span>';
			$hall_array = array();
			foreach ($halls as $hall) {
				$hall_array[] = $hall->name;
				//echo $hall->name;
			}
			echo implode(", ", $hall_array);
			echo '<br/>';
		}
		if($stand)    echo '<span class="details-heading"><strong>Stand: </strong></span>'.$stand.'<br/>';
		if ($display_categories == 0) { 
			if($category) echo '<span class="details-heading"><strong>Category: </strong></span>'.$cats_short; 
		}
		echo '</p>';
	 ?>

	<!-- The Modal -->
	<div id="myModal" class="modal">
	  <!-- Modal content -->
	  <div class="modal-content">
	    <span class="close">&times;</span>
	    <p><b>FULL LIST OF CATEGORIES:</b></p>
	    <p><?php echo $category; ?></p>
	  </div>
	</div>

<script>		
	
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

</script>	
	


	 <!-- CONTACT DETAILS -->
	 <?php if($contact_name || $email || $website || $linkedin || $twitter || $facebook || $google || $instagram || $pinterest || $youtube || $telephone || $address || $new_address):
		echo '<p class="contact-details">'; ?>
		<!-- Contact Name -->
		<?php if($contact_name){ echo '<span class="details-heading"><strong>Contact Name: </strong></span><span class="details-text">'.$contact_name.'</span><br/>'; }?>
		
		<!-- Phone Number -->
		<?php if($telephone){ echo '<span class="details-heading"><strong>Telephone: </strong></span><span class="details-text">'.$telephone.'</span><br/>'; }?>
		
		<!-- Email -->
		<?php if($email){ echo '<span class="details-heading"><strong>Email: </strong></span><span class="details-text"><a href="mailto:'.$email.'" title="'.$email.'">'.$email.'</a></span><br/>'; }?>
		
		<!-- Website -->
		<?php if($website){ echo '<span class="details-heading"><strong>Website: </strong></span><span class="details-text"><a href="'.$website.'" title="'.$website.'" target="_blank">'.$website.'</a></span><br/>'; }?>
		
		<!-- Address -->
		<?php if(empty($new_address[0]) && empty($new_address[1]) && empty($new_address[2]) && empty($new_address[3]) && empty($new_address[4]) && empty($new_address[5]) && empty($new_address[6])){ 
			if($address){ echo '<span class="details-heading"><strong>Address: </strong></span><span class="details-text">'.$address.'</span><br/>'; } 
		}?>
		
		<!-- New Address -->
		<?php if($new_address[0] || $new_address[1] || $new_address[2] || $new_address[3] || $new_address[4] || $new_address[5] || $new_address[6]){ echo '<span class="details-heading"><strong>Address: </strong></span><span class="details-text">'; }?>
		<?php if($new_address[0]){ echo $new_address[0] . ', '; }?>
		<?php if($new_address[1]){ echo $new_address[1] . ', '; }?>
		<?php if($new_address[2]){ echo $new_address[2] . ', '; }?>
		<?php if($new_address[3]){ echo $new_address[3] . ', '; }?>
		<?php if($new_address[4]){ echo $new_address[4] . ', '; }?>
		<?php if($new_address[5]){ echo $new_address[5] . ', '; }?>
		<?php if($new_address[6]){ echo $new_address[6]; }?>
		
		<?php if($new_address[0] || $new_address[1] || $new_address[2] || $new_address[3] || $new_address[4] || $new_address[5] || $new_address[6]) { echo '</span><br/>'; } ?>
		
		<!-- Facebook -->
		<?php if($facebook){ echo '<a href="'.$facebook.'" title="'.$facebook.' on Facebook" target="_blank"><i class="social_media fa fa-facebook-official" aria-hidden="true"></i></a>'; }?>
		
		<!-- Google -->
		<?php if($google){ echo '<a href="'.$google.'" title="'.$google.' on LinkedIn" target="_blank"><i class="social_media fa fa-google-plus-square" aria-hidden="true"></i></a>'; }?>
		
		<!-- Instagram -->
		<?php if($instagram){ echo '<a href="'.$instagram.'" title="'.$instagram.' on Twitter" target="_blank"><i class="social_media fa fa-instagram" aria-hidden="true"></i></a>'; }?>
		
		<!-- LinkedIn -->
		<?php if($linkedin){ echo '<a href="'.$linkedin.'" title="'.$linkedin.' on LinkedIn" target="_blank"><i class="social_media fa fa-linkedin-square" aria-hidden="true"></i></a>'; }?>
		
		<!-- Pinterest -->
		<?php if($pinterest){ echo '<a href="'.$pinterest.'" title="'.$pinterest.' on Facebook" target="_blank"><i class="social_media fa fa-pinterest-square" aria-hidden="true"></i></a>'; }?>
		
		<!-- Twitter -->
		<?php if($twitter){ echo '<a href="'.$twitter.'" title="'.$twitter.' on Twitter" target="_blank"><i class="social_media fa fa-square-x-twitter" aria-hidden="true"></i></a>'; }?>
		
		<!-- Youtube -->
		<?php if($youtube){ echo '<a href="'.$youtube.'" title="'.$youtube.' on Twitter" target="_blank"><i class="social_media fa fa-youtube-square" aria-hidden="true"></i></a>'; }?>
		
		<?php echo '</p>'; ?>
	 <?php endif; // email|website|twitter|telephone ?>

 
 <!-- BIOGRAPHY -->
 <?php   
	$profile = get_post_meta($post->ID, 'ex_rich_text_profile', true);
	
	// TJW SSP-406 

	echo '<div id="ex-enhanced" class="' . $enhanced_exhibitor_detail . '">' ;
	
	if(!empty($profile)){ 
	 echo '<div id="company-profile">';
	 echo '<h2>Company Profile</h2>';
	 echo wpautop($profile);
	 echo '</div>';
	}
	
	// TJW SSP-406 
	echo '</div> ';
	
	?>

 <?php
 	/** Attachments / Promo Docs */
	get_template_part('content/exhibitors/exhibitor', 'attachments');
	
	/** Brochure */
 	get_template_part('content/exhibitors/exhibitor', 'brochures');
 
	/** Videos */
	$url = get_post_meta( $post->ID, 'ex_videos', true );
	
	get_template_part('content/exhibitors/exhibitor', 'video');
	
	/** Images */
	get_template_part('content/exhibitors/exhibitor', 'image');
	
	/** Images */
	get_template_part('content/exhibitors/exhibitor', 'brands');

	/** Press Releases */
	get_template_part('content/exhibitors/exhibitor', 'press-releases');
	
	//echo '</div> <!-- TJ -->';

	/** Appointments */  
	//get_template_part('content/exhibitors/exhibitor-appointment');
	
	// TJW adding back in the appointments if we have the active extranet 			
	// only switch on if we have the post type 'appointments' which is set up in the miramedia-plugin-extranet plugin
	
	// SSP-600 additionally only switch on if the meta data appointments_off is != 1
	
	$path = 'miramedia-plugin-extranet/miramedia-exhibitors-plugin.php';
	
	if ($hide_appointment_system) {
		// not using the appointment system
	}
	else {
		// check to see if appointment system is used at the exhibitor level.
		$appointments_off = rwmb_meta('appointments_off');
			
			//if ( is_plugin_active( $path ) ) {
		if ( is_plugin_active( $path ) && !($appointments_off) ) {
			get_template_part('content/exhibitors/exhibitor-appointment');
		}
	}
			
	if ( current_user_can( 'administrator' ) ) {
		echo do_shortcode( '[seminarsbartag se_exhibitor="'. $post_name .'" se_layout="nospeakers"]' );
	}
	
 ?>
 </div>
 <p class="back-btn-wrapper"><span class="back-btn" onClick="parent.history.back();">&laquo; Back</span></p>
 <?php endwhile; endif; // have_post|while_post?>
<!--
<div class="previous_exhibitor"> <?php //previous_post_link(); ?> </div>
<div class="next_exhibitor"> <?php //next_post_link(); ?> </div>
-->
 <div class="clear"></div>
			</div>
		</main>
		<?php 
		if($page_layout == 'sidebar'){
			get_sidebar();
		}
		?>
	</div>
</div>
<?php get_footer(); ?>
