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

$MEMBERSHIPS = array('Membership - Individual' => 'individual', 'Membership - Joint' => 'joint');

function generate_ukfl_number(){
	global $wpdb;
	$year = date('y');
	$sql = "select max(user_login) from {$wpdb->prefix}users where user_login like '$year%'";
	$ukfl_no = $wpdb->get_var( $sql );
	if (!isset($ukfl_no)){ $ukfl_no = str_pad($year, 5, '0', STR_PAD_RIGHT); }
	$ukfl_no++;
	return $ukfl_no;
}

function generate_ukfl_dog_number($handler_ukfl){
	global $wpdb;
	$sql = "select max(dog_ukfl) from {$wpdb->prefix}ukfl_dogs where owner_ukfl='".$handler_ukfl."'";
	$ukfl_no = $wpdb->get_var( $sql );
	if (!isset($ukfl_no)){ $ukfl_no = 'A'; }
	else{ $ukfl_no++; }
	return $ukfl_no;
}

function create_ukfl_member($first_name, $last_name, $email){
	$password = wp_generate_password( 12, true );
	$ukfl_no = generate_ukfl_number();
	$user_id = wp_create_user ( $ukfl_no, $password, $email );
	wp_update_user(
			array(
					'ID'		=> $user_id,
					'first_name'=> $first_name,
					'last_name'	=> $last_name,
					'nickname'	=> $first_name.' '.$last_name
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
	wp_mail( $email, '['.get_bloginfo('name').'] Welcome to UKFL!', $msg, $headers );
	
	$admin_msg = 'New user registration on '.get_bloginfo('name').':<br /><br />
	UKFL Number: <strong>'.$ukfl_no.'</strong><br />
	Name: <strong>'.$first_name.' '.$last_name.'</strong><br />
	E-mail: <strong>'.$email.'</strong><br />';
	wp_mail(get_option('admin_email'), '['.get_bloginfo('name').'] New User Registration', $admin_msg, $headers);
	
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
	add_role('ukfl_member', 'UKFL Member',
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

