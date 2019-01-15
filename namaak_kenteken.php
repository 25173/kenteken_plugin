<?php
/*
Plugin Name:  namaak kenteken check
Plugin URI:   http://www.stackey.nl/bewijzenmap/periode2.1/cms/wordpress/
Description:  een invul frame om je kenteken in te voeren. er wordt automatisch een database aangemaakt en voor display gebruik je [licensePlateCheck].
Version:      1.0.0
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
	wp_enqueue_script( 'kenteken', plugin_dir_url( __FILE__ ) . 'js/scriptKenteken.js', array( 'jquery' ), null, true );

}
add_action( 'wp_enqueue_scripts', 'wpse_load_plugin_css' );


function kentekenCheckForm() {
	// see if we can find a license plate
	if ( isset( $_POST["kenteken"] ) ) {
		global $wpdb;

		$kenteken = $_POST["kenteken"];
		// remove html char
		$kenteken = htmlspecialchars($kenteken);
		// remove the special charater like '-'
		$kenteken = RemoveSpecialChapr($kenteken);
		// set all to low chase
		$kenteken = strtolower($kenteken);

		echo "<table class='licensePlateResult'>";

		$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}license_Plate WHERE licensePlate  LIKE '$kenteken'");
		if ($result == NULL){
			echo 'No result for <br> license plate:'  . $kenteken . '<br>';
//			echo $wpdb->last_error;
		}else {
			foreach ( $result as $row ) {
 				if ($row->pay == 1){
					$name = $row->name;
					$lname = $row->Lname;
					$km = $row->km;
				}else{
					$name = '<a href="#">€3,99</a>';
					$lname = '<a href="#">€3,99</a>';
					$km = '<a href="#">€1,99</a>';
				}
				echo '<tr><th>owner</th> <td> ' . $name . '</td></tr>';
				echo '<tr><th>owner last name</th><td>' . $lname . '</td></tr>';
				echo '<tr><th>brand</th><td> ' . $row->brand . '</td></tr>';
				echo '<tr><th>license plate</th><td>' . $row->licensePlateReal . '</td></tr>';
				echo '<tr><th>km</th><td>'. $km.'</td></tr>';
			}
		}
	echo    "               
			</table>";

	} else {
		echo "<div class='gratisKentekenForm'>
				<div class='gratisKentekenTitel'>
					<h2>Gratis kenteken check</h2>
				</div>
				<div class='grid'>
			 	<form class='kentekenForm' action='" . $_SERVER['REQUEST_URI'] . "' method='post'>
			 		<label for='kenteken'> vul hier je kenteken in:</label>
					 <input type='text' id='kenteken' name='kenteken' required placeholder='43-rh-jv'><br>
			 		<input type='submit' id='submit_kenteken' value='Bekijk gratis rapport' name='submit_kenteken' >
			 	</form>
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
}

add_shortcode('licensePlateCheck', 'kentekenCheckForm');

// making a database table for the license plate of all cars
function my_plugin_create_db() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'license_Plate';

	// check if it exist or not
	if ($wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name) {
//		create table for the license Plate
		$sql = 'CREATE TABLE ' . $table_name . '(
		id INTEGER(10) UNSIGNED AUTO_INCREMENT,
		name TEXT(255),
		Lname TEXT(255),
		licensePlate VARCHAR(20),
		licensePlateReal VARCHAR(225),
		brand VARCHAR(225),
		km INT(20),
		pay INT DEFAULT 0 NOT NULL, 
		PRIMARY KEY  (id) )';

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		add_option('kenteken_databaseversion','1.0');
	}
}

register_activation_hook( __FILE__, 'my_plugin_create_db' );


// a function that remove all the special character
function RemoveSpecialChapr($value){
	$title = str_replace( array('-','/','_','.'),  '', $value);

	return $title;
}