<?php
/**
* Plugin Name: UKFL Plugin
* Description: UKFL plugin - manage flyball teams/dogs/handlers/events.
* Version: 0.0.1
* Author: PawPrints Design
*
* Text Domain: ukfl
*
* @package UKFL Plugin
* @category Core
* @author PawPrints Design
*/

// Include widget/files...
require_once 'posttypes.php';
require_once 'custom-metaboxes.php';
require_once 'ukfl-gocardless.php';

$MEMBERSHIPS = array('Membership - Individual' => 'individual', 'Membership - Joint' => 'joint', 'Membership - Junior' => 'junior');

function add_calendar_feed(){
	add_feed('calendar', 'export_events');
}
add_action('init', 'add_calendar_feed');


function export_events(){
	global $wpdb;

	$all_events = get_posts(array(
                'post_status'   => 'publish',
                'post_type'      => 'ukfl_event',
                'posts_per_page' => -1,
                'order'          => 'ASC',
                'meta_key'       => 'ukfl_event_start_date',
                'orderby'       => 'meta_value_num',
                'meta_query'    => array(
                                array('key' => 'ukfl_event_start_date', 'value' => date('Ymd'), 'compare' => '>=')
                )
	));

	//Give the iCal export a filename
        $filename = urlencode( 'UKFL-events-ical-' . date('Y-m-d') . '.ics' );
	$timestamp = date_i18n('Ymd\THis',time(), true);
        $eol = "\r\n";
        //Collect output
        ob_start();
        // Set the correct headers for this file
//        header("Content-Description: File Transfer");
//	header("Content-Disposition: attachment; filename=".$filename);
        header('Content-type: text/calendar; charset=utf-8');
        header("Pragma: 0");
        header("Expires: 0");

?>
BEGIN:VCALENDAR<?php echo $eol; ?>
VERSION:2.0<?php echo $eol; ?>
X-WR-TIMEZONE:Europe/London<?php echo $eol; ?>
METHOD:PUBLISH<?php echo $eol; ?>
PRODID:-//<?php echo get_bloginfo('name'); ?> //Event Dates//EN<?php echo $eol; ?>
CALSCALE:GREGORIAN<?php echo $eol; ?>
X-WR-CALNAME:<?php echo get_bloginfo('name').$eol;?>
<?php
	foreach($all_events as $event) : setup_postdata($event);
                $start_date = DateTime::createFromFormat('Ymd', get_post_meta( $event->ID, 'ukfl_event_start_date', true ));
                $end_date   = DateTime::createFromFormat('Ymd', get_post_meta( $event->ID, 'ukfl_event_end_date', true ));
		$end_date->add(new DateInterval('P1D'));
		$created_date = get_post_time('Ymd\THis\Z', true, $event->ID );
		$post_modified = get_the_modified_date('Ymd\THis\Z', $event->ID );
		$open_date = DateTime::createFromFormat('Ymd H:i:s', get_post_meta( $event->ID, 'ukfl_event_open_date', true )." 20:00:00");

//A - The actual event... ?>
BEGIN:VEVENT<?php echo $eol; ?>
SUMMARY:<?php echo get_post_meta($event->ID, 'ukfl_event_title', true)." (".get_the_title($event->post_parent).")".$eol; ?>
ORGANIZER:<?php echo get_the_title($event->post_parent).$eol; ?>
UID:<?php echo $event->post_title."_A".$eol; ?>
CREATED:<?php echo $created_date.$eol; ?>
DTSTAMP:<?php echo $timestamp.$eol; ?>
DTSTART:<?php echo $start_date->format('Ymd').$eol; ?>
DTEND:<?php echo $end_date->format('Ymd').$eol; ?>
LAST-MODIFIED:<?php echo $post_modified.$eol; ?>
LOCATION:<?php echo get_post_meta($event->ID, 'ukfl_event_venue', true).", ".get_post_meta( $event->ID, 'ukfl_event_postcode', true ).$eol; ?>
GEO:<?php echo get_post_meta( $event->ID, 'ukfl_event_lat', true).",".get_post_meta( $event->ID, 'ukfl_event_long', true).$eol; ?>
END:VEVENT<?php echo $eol; ?>
<?php
//B - Open date... ?>
BEGIN:VEVENT<?php echo $eol; ?>
SUMMARY:SCHEDULE OUT - <?php echo get_post_meta($event->ID, 'ukfl_event_title', true)." (".$start_date->format('jS M').")".$eol; ?>
UID:<?php echo $event->post_title."_B".$eol; ?>
CREATED:<?php echo $created_date.$eol; ?>
DTSTAMP:<?php echo $timestamp.$eol; ?>
DTSTART:<?php echo $open_date->format('Ymd\THis').$eol; ?>
<?php $open_date->add(new DateInterval('PT1H')); ?>
DTEND:<?php echo $open_date->format('Ymd\THis').$eol; ?>
LAST-MODIFIED:<?php echo $post_modified.$eol; ?>
END:VEVENT<?php echo $eol; ?>
<?php endforeach;?>
END:VCALENDAR<?php echo $eol; ?>
<?php
	$eventsical = ob_get_contents();
        ob_end_clean();
        echo $eventsical;
        exit();

}

function generate_ukfl_number(){
	global $wpdb;
	$year = date('y');
	$sql = "select max(user_login) from {$wpdb->prefix}users where user_login like '$year%'";
	$ukfl_no = $wpdb->get_var( $sql );
	if (!isset($ukfl_no)){ $ukfl_no = str_pad($year, 5, '0', STR_PAD_RIGHT); }
	else{ $ukfl_no++; }
	return $ukfl_no;
}

function generate_ukfl_dog_number($owner_ukfl){
	global $wpdb;
	$sql = "select group_concat(post_id) as post_ids from {$wpdb->prefix}postmeta where meta_key='ukfl_dog_owner' and meta_value='".$owner_ukfl."' group by meta_key,meta_value";
	$post_ids = $wpdb->get_var( $sql );
	if (!isset($post_ids)){ return 'A'; }
	
	$sql = "select max(meta_value) from {$wpdb->prefix}postmeta where meta_key='ukfl_dog_letter' and post_id in ($post_ids)";
	//$sql = "select max(dog_ukfl) from {$wpdb->prefix}ukfl_dogs where owner_ukfl='".$handler_ukfl."'";
	$ukfl_no = $wpdb->get_var( $sql );
	
	if (!isset($ukfl_no)){ $ukfl_no = 'A'; }
	else{ $ukfl_no++; }
	return $ukfl_no;
}

function create_ukfl_member($first_name, $last_name, $email){
	$password = wp_generate_password( 12, true );
	$ukfl_no = generate_ukfl_number();
	$send_email = 1;
	if ($email == '' || $email == ' '){ $email = $ukfl_no.'@ukflyball.org.uk'; $send_email = 0; }
	$user_id = wp_create_user ( $ukfl_no, $password, $email );
	wp_update_user(
			array(
					'ID'		=> $user_id,
					'first_name'=> $first_name,
					'last_name'	=> $last_name,
					'nickname'	=> $first_name.' '.$last_name,
					'display_name'	=> $first_name.' '.$last_name
			)
	);
	$user = new WP_User($user_id);
	$user->set_role('subscriber');
	$user->add_role('ukfl_member');
	
	$msg = 'Dear '.$first_name.',<br /><br />
Welcome to the United Kingdom Flyball League (UKFL) and thank-you for joining us.<br /><br />
	
	Your UKFL Number is: <strong>'.$ukfl_no.'</strong><br />
	Your password is: <strong>'.$password.'</strong><br />

<p>You can use your UKFL Number or the email address registered to your account ('.$email.') to login to the <a href="'.home_url().'">UKFL website</a>.<br />
Please feel free to <a href="'.esc_url( wp_login_url() ).'">login to your account</a> and start adding your dogs, clubs and events.</p>

<p>We look forward to seeing you in the lanes very soon.<br /><br />UK Flyball League</p>';
	
	$headers = array('Content-Type: text/html; charset=UTF-8');
	if ($send_email) {
		wp_mail( $email, '['.get_bloginfo('name').'] Welcome to UKFL!', $msg, $headers ); 
	
		$admin_msg = 'New user registration on '.get_bloginfo('name').':<br /><br />
		UKFL Number: <strong>'.$ukfl_no.'</strong><br />
		Name: <strong>'.$first_name.' '.$last_name.'</strong><br />
		E-mail: <strong>'.$email.'</strong><br />';
		wp_mail(get_option('admin_email'), '['.get_bloginfo('name').'] New User Registration', $admin_msg, $headers);
	}
	
	return $user;
}

function add_roles_on_activation() {
	add_role( 'team_captain', 'Team Captain', 
		array(
			'read' => true,
			'level_0' => true,
			'level_1' => true,
			'delete_posts' => true,
			'edit_posts' => true
		)
	);
	add_role('tournament_organiser', 'Tournament Organiser',
                array(
                        'read' => true,
                        'level_0' => true,
                        'level_1' => true,
                        'delete_posts' => true,
                        'edit_posts' => true
                )
	);
	add_role('ukfl_junior', 'Junior',
		array(
			'read' => true,
                        'level_0' => true,
		)
	);
	add_role('ukfl_member', 'UKFL Member',
		array(
			'read' => true,
                        'level_0' => true,
		)
	);
        add_role('ukfl_official', 'UKFL Official',
                array(
                        'read' => true,
                        'level_0' => true,
                )
        );
}
function del_roles_on_uninstall() {
	remove_role('team_captain');
	remove_role('tournament_organiser');
}
register_activation_hook( __FILE__, 'add_roles_on_activation' );
register_uninstall_hook( __FILE__, 'del_roles_on_uninstall' );


/**
 * New user registrations should have display_name set 
 * to 'firstname lastname'. This is best used on the
 * 'user_register' action.
 *
 * @param int $user_id The user ID
 */
function set_default_display_name( $user_id ) {
	$user = get_userdata( $user_id );
	$name = sprintf( '%s %s', $user->first_name, $user->last_name );
	$args = array(
		'ID'           => $user_id,
		'display_name' => $name,
		'nickname'     => $name
	);
	wp_update_user( $args );
}
add_action( 'user_register', 'set_default_display_name' );


function my_acf_google_map_api ($api){
	$api['key'] = 'AIzaSyADYSpYgbtKJB37J-zeheWeglwHPsUQ3jw';
	return $qpi;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');
