<?php
/**
* Plugin Name: FlyballPress
* Description: Based on SportsPress - manage flyball teams/dogs/handlers/events.
* Version: 0.0.1
* Author: PawPrints Design
*
* Text Domain: flyballpress
*
* @package FlyballPress
* @category Core
* @author PawPrints Design
*/

// Include widget/files...
require_once 'posttypes.php';
require_once 'custom-metaboxes.php';


function add_roles_on_activation() {
	add_role( 'team_captain', 'Team Captain', 
		array(
			'read' => true,
			'level_0' => true,
			'level_1' => true,
                        'read' => true,
			'delete_posts' => true,
			'edit_posts' => true
		)
	);
	add_role('tournament_organiser', 'Tournament Organiser',
                array(
                        'read' => true,
                        'level_0' => true,
                        'level_1' => true,
                        'read' => true,
                        'delete_posts' => true,
                        'edit_posts' => true
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

