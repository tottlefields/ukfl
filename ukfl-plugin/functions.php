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

function generate_ukfl_number(){
	global $wpdb;
	$year = date('y');
	$sql = "select max(user_login) from {$wpdb->prefix}users where user_login like '$year%'";
	$ukfl_no = $wpdb->get_var( $sql );
	if (!isset($ukfl_no)){ $ukfl_no = str_pad($year, 6, '0', STR_PAD_RIGHT); }
	$ukfl_no++;
	return $ukfl_no;
}

function generate_ukfl_dog_number($handler_ukfl){
	global $wpdb;
	$sql = "select max(dog_ukfl) from {$wpdb->prefix}dogs where owner_ukfl='".$handler_ukfl."'";
	$ukfl_no = $wpdb->get_var( $sql );
	if (!isset($ukfl_no)){ $ukfl_no = 'A'; }
	else{ $ukfl_no++; }
	return $ukfl_no;
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

