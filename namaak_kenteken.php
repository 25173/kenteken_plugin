<?php
/*
Plugin Name:  namaak kenteken check
Plugin URI:   http://www.stackey.nl/bewijzenmap/periode2.1/cms/wordpress/
Description:  een invul frame om je kenteken in te voeren. het wordt doorverwezen naar een ander website die meer eigenschapen verteld over de auto. de shortcode is [licensePlateCheck].
Version:      2.0.0
Author:       Timothy Falorni
Author URI:   falorni.nl
License:      GPL2
License URI:  http://www.stackey.nl/bewijzenmap/periode2.1/cms/wordpress/
Text Domain:  Timothy
Domain Path:  /languages
*/

global $reg_errors;
$reg_errors = new WP_Error;

// het laden van css en javascript
function wpse_load_plugin_css() {
	$plugin_url = plugin_dir_url( __FILE__ );

	wp_enqueue_style( 'kenteken', $plugin_url . 'css/kenteken.css' );
	wp_enqueue_script( 'kenteken', $plugin_url . 'js/scriptKenteken.js', array( 'jquery' ), null, true );

}
add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_css' );


function kentekenCheckForm() {
	// see if we can find a license plate
		echo "<div class='gratisKentekenForm'>
				<div class='gratisKentekenTitel'>
					<h2>Gratis kenteken check</h2>
				</div>
				<div class='grid'>
			 	<div class='kentekenForm'>
			 		<label for='kenteken'> vul hier je kenteken in:</label>
					 <input type='text' id='kenteken' name='kenteken' required placeholder='XX-123-X'><br>
			 	<button  id='submit_kenteken' >Bekijk gratis rapport</button>
			 	</div>				
			 	<div class='kentekenchecks'>
					 <ul>
					 	<li>exacte waarde</li>
					 	<li>eigenaren historie</li>
					 	<li>alles over techniek</li>
					 	<li>aantal spots </li>
					 	<li>goedkoop, maar uitgebreid</li>
					</ul>
				</div>
			 </div>
			 </div>";

}

add_shortcode('licensePlateCheck', 'kentekenCheckForm');


