<?php


// Our custom post type function
function register_custom_posttypes() {

	$club_args = array(
			'labels' => array(
					'name' 			=> __( 'Clubs', 'ukfl' ),
					'singular_name' 	=> __( 'Club', 'ukfl' ),
					'add_new_item' 		=> __( 'Add New Club', 'ukfl' ),
					'edit_item' 		=> __( 'Edit Club', 'ukfl' ),
					'new_item' 		=> __( 'New', 'ukfl' ),
					'view_item' 		=> __( 'View Club', 'ukfl' ),
					'search_items' 		=> __( 'Search', 'ukfl' ),
					'not_found' 		=> __( 'No results found.', 'ukfl' ),
					'not_found_in_trash' 	=> __( 'No results found.', 'ukfl' ),
					'featured_image'	=> __( 'Logo', 'ukfl' ),
					'set_featured_image' 	=> __( 'Select Logo', 'ukfl' ),
					'remove_featured_image' => __( 'Remove Logo', 'ukfl' ),
					'use_featured_image' 	=> __( 'Select Logo', 'ukfl' ),
			),
			'public' 		=> true,
			'show_ui' 		=> true,
			//'capability_type' 	=> 'ukfl_club',
			//'map_meta_cap' 	=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> false,
			'hierarchical'		=> false,
			'rewrite'		=> array( 'slug' => get_option('ukfl_club_slug','club') ),
			'supports' 		=> array( 'title', 'author', 'thumbnail' ),
			'has_archive' 		=> false,
			'show_in_nav_menus' 	=> true,
			'menu_icon' 		=> 'dashicons-shield',
			//'show_in_rest' 			=> true,
			//'rest_controller_class' => 'SP_REST_Posts_Controller',
			//'rest_base' 			=> 'teams',
	);

	$team_args = array(
		'labels' => array(
                        'name'          		=> __( 'Teams', 'ukfl' ),
                        'singular_name'         => __( 'Team', 'ukfl' ),
                        'add_new_item'          => __( 'Add New Team', 'ukfl' ),
                        'edit_item'             => __( 'Edit Team', 'ukfl' ),
                        'new_item'              => __( 'New', 'ukfl' ),
                        'view_item'             => __( 'View Team', 'ukfl' ),
                        'search_items'          => __( 'Search', 'ukfl' ),
                        'not_found'             => __( 'No results found.', 'ukfl' ),
                        'not_found_in_trash'    => __( 'No results found.', 'ukfl' ),
		),
                'public'                => true,
                'show_ui'               => true,
                'publicly_queryable'    => true,
                'exclude_from_search'   => false,
                'hierarchical'          => false,
                'rewrite'               => array( 'slug' => get_option('ukfl_team_slug','team') ),
                'supports'              => array( 'title', 'author' ),
                'has_archive'           => false,
                'menu_icon'             => 'dashicons-shield-alt',
	);

	register_post_type('ukfl_club', $club_args);
	register_post_type('ukfl_team', $team_args);
}
// Hooking up our function to theme setup
add_action( 'init', 'register_custom_posttypes' );

function is_ukfl_club(){
	global $wp_query;
	if ($wp_query->query_vars['post_type'] == 'ukfl_club') return true;
	return false;
}

function is_ukfl_team(){
        global $wp_query;
        if ($wp_query->query_vars['post_type'] == 'ukfl_team') return true;
        return false;
}


// List Clubs/Teams alphabetically
add_filter('posts_orderby', 'club_team_orderby');
function club_team_orderby($sql){
	global $wpdb, $wp_query;
	if (is_admin() && (is_ukfl_club() || is_ukfl_team())){
		return $wpdb->prefix."posts.post_title ASC";
	}
	return $sql;
}


//Set up admin tables for teams/clubs
add_filter('manage_posts_columns', 'ukfl_club_custom_columns');
add_action('manage_posts_custom_columns', 'ukfl_club_show_columns');
function ukfl_club_custom_columns($defaults) {
	global $wp_query;
	if (is_ukfl_club()){
		$defaults['club_logo'] = 'Club Logo';
		$defaults['team_captain'] = 'Team Captain';
	}
	return $defaults;
}
function ukfl_club_show_columns($name){
	global $post;
	switch ($name) {
		case 'team_captain':
			echo $post->post_author;
			break;
		case 'club_logo':
			if($has_post_thumbnail($post->ID)) echo get_the_post_thumbnail($post->ID);
			break;
	}
}




?>
