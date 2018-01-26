<?php


// Our custom post type function
function register_custom_posttypes() {

	$team_args = array(
			'labels' => array(
					'name' 				=> __( 'Teams', 'ukfl' ),
					'singular_name' 	=> __( 'Team', 'ukfl' ),
					'add_new_item' 		=> __( 'Add New Team', 'ukfl' ),
					'edit_item' 		=> __( 'Edit Team', 'ukfl' ),
					'new_item' 			=> __( 'New', 'ukfl' ),
					'view_item' 		=> __( 'View Team', 'ukfl' ),
					'search_items' 		=> __( 'Search', 'ukfl' ),
					'not_found' 		=> __( 'No results found.', 'ukfl' ),
					'not_found_in_trash' 	=> __( 'No results found.', 'ukfl' ),
					'featured_image'		=> __( 'Logo', 'ukfl' ),
					'set_featured_image' 	=> __( 'Select Logo', 'ukfl' ),
					'remove_featured_image' => __( 'Remove Logo', 'ukfl' ),
					'use_featured_image' 	=> __( 'Select Logo', 'ukfl' ),
			),
			'public' 				=> true,
			'show_ui' 				=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> false,
			'hierarchical'			=> false,
			'rewrite'				=> array( 'slug' => get_option('ukfl_team_slug','teams') ),
			'supports' 				=> array( 'title', 'author', 'thumbnail' ),
			'has_archive' 			=> false,
			'show_in_nav_menus' 	=> true,
			'menu_icon' 			=> 'dashicons-shield',
			'menu_position'			=> 30,
	);

	$sub_team_args = array(
		'labels' => array(
                        'name'          		=> __( 'Sub Teams', 'ukfl' ),
                        'singular_name'         => __( 'Sub Team', 'ukfl' ),
                        'add_new_item'          => __( 'Add New Sub Team', 'ukfl' ),
                        'edit_item'             => __( 'Edit Sub Team', 'ukfl' ),
                        'new_item'              => __( 'New', 'ukfl' ),
                        'view_item'             => __( 'View Sub Team', 'ukfl' ),
                        'search_items'          => __( 'Search', 'ukfl' ),
                        'not_found'             => __( 'No results found.', 'ukfl' ),
                        'not_found_in_trash'    => __( 'No results found.', 'ukfl' ),
		),
			'public'                => true,
			'show_ui'               => true,
			'publicly_queryable'    => true,
			//'exclude_from_search'   => false,
			'exclude_from_search'   => true,
			'hierarchical'          => false,
			'rewrite'               => array( 'slug' => get_option('ukfl_sub_team_slug','sub-teams') ),
			'supports'              => array( 'title', 'author' ),
			'has_archive'           => false,
			'menu_icon'             => 'dashicons-shield-alt',
			'menu_position'			=> 31,
	);
	
	$event_args = array(
		'labels' => array(
                        'name'                  => __( 'Events', 'ukfl' ),
                        'singular_name'         => __( 'Event', 'ukfl' ),
                        'add_new_item'          => __( 'Add New Event', 'ukfl' ),
                        'edit_item'             => __( 'Edit Event', 'ukfl' ),
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
                'rewrite'               => array( 'slug' => get_option('ukfl_event_slug','events') ),
                'has_archive'           => true,
                'menu_icon'             => 'dashicons-location-alt',
				'menu_position'			=> 33,
	);
	
	$dog_args = array(
		'labels' => array(
                        'name'                  => __( 'Dogs', 'ukfl' ),
                        'singular_name'         => __( 'Dog', 'ukfl' ),
                        'add_new_item'          => __( 'Add New Dog', 'ukfl' ),
                        'edit_item'             => __( 'Edit Dog', 'ukfl' ),
                        'new_item'              => __( 'New', 'ukfl' ),
                        'view_item'             => __( 'View Dog', 'ukfl' ),
                        'search_items'          => __( 'Search', 'ukfl' ),
                        'not_found'             => __( 'No results found.', 'ukfl' ),
                        'not_found_in_trash'    => __( 'No results found.', 'ukfl' ),
                ),
                'public'                => true,
                'show_ui'               => true,
                'publicly_queryable'    => true,
                'exclude_from_search'   => false,
                'hierarchical'          => false,
                'rewrite'               => array( 'slug' => get_option('ukfl_dog_slug','dogs') ),
				'supports' 				=> array( 'title', 'author', 'thumbnail' ),
                'has_archive'           => false,
				'menu_position'			=> 32,
	);

	register_post_type('ukfl_team',  $team_args);
	register_post_type('ukfl_sub-team',  $sub_team_args);
	register_post_type('ukfl_event', $event_args);
	register_post_type('ukfl_dog', $dog_args);
	
	register_taxonomy('dog-breeds', array('ukfl_dog'),
			array(
					'hierarchical' => false,
					'label' => 'Dog Breeds',
					'singular_label' => 'Dog Breed',
					'rewrite' => true,
					'capabilities' => array(
							'assign_terms' => 'read'
					)
			)
	);
	
	register_taxonomy('team-regions', array('ukfl_team'),
			array(
					'hierarchical' => false,
					'label' => 'UKFL Regions',
					'singular_label' => 'UKFL Region',
					'rewrite' => true,
					'capabilities' => array(
							'assign_terms' => 'read'
					)
			)
	);
}
// Hooking up our function to theme setup
add_action( 'init', 'register_custom_posttypes' );

function fontawesome_icon_dashboard() {
	echo "<style type='text/css' media='screen'>
			#adminmenu #menu-posts-ukfl_dog div.wp-menu-image:before {
				font-family: Fontawesome !important;
				content: '\\f1b0';
			}";
}
add_action('admin_head', 'fontawesome_icon_dashboard');

function set_admin_menu_separator() { do_action( 'admin_init', 29 ); }
add_action( 'admin_menu', 'set_admin_menu_separator' );

function is_ukfl_team(){
	global $wp_query;
	if ($wp_query->query_vars['post_type'] == 'ukfl_team') return true;
	return false;
}

function is_ukfl_sub_team(){
        global $wp_query;
        if ($wp_query->query_vars['post_type'] == 'ukfl_sub-team') return true;
        return false;
}

function is_ukfl_dog(){
        global $wp_query;
        if ($wp_query->query_vars['post_type'] == 'ukfl_dog') return true;
        return false;
}

function is_ukfl_event(){
        global $wp_query;
        if ($wp_query->query_vars['post_type'] == 'ukfl_event') return true;
        return false;
}


// List Clubs/Teams alphabetically
add_filter('posts_orderby', 'club_team_orderby');
function club_team_orderby($sql){
	global $wpdb, $wp_query;
	if (is_admin() && (is_ukfl_team() || is_ukfl_sub_team())){
		return $wpdb->prefix."posts.post_title ASC";
	}
	return $sql;
}


//Set up admin tables for teams/clubs
add_filter( 'manage_posts_columns', 'ukfl_team_custom_columns' );
add_action( 'manage_posts_custom_column' , 'ukfl_team_show_columns', 10, 2 );

function ukfl_team_custom_columns($defaults) {
	global $wp_query;
	if (is_ukfl_team()){
		$defaults['region'] = 'UKFL Region';
		$defaults['club_logo'] = 'Club Logo';
		$defaults['author'] = 'Team Captain';
		$defaults['title'] = 'Team Name';
		return $defaults;
	}
	if (is_ukfl_dog()){
		unset($defaults['date']);
		$defaults['title'] = "UKFL No.";
	        $defaults['dog_name'] = "Dog's Name";
                $defaults['author'] = 'Owner';
                $defaults['team_name'] = 'Team';
	}
	return $defaults;
}
function ukfl_team_show_columns($column, $post_id){
	switch ($column) {
		case 'region':
			echo get_the_term_list( $post_id, 'team-regions', '', ', ' );
			break;
		case 'club_logo':
			echo get_the_post_thumbnail($post_id, 'thumbnail');
			break;
                case 'dog_name':
                        echo get_post_meta($post_id, 'ukfl_dog_name', true) ;
                        break;
		case 'team_name':
			$club_id = wp_get_post_parent_id($post_id);
			echo get_the_title( $club_id );
			break;	
	}
}


