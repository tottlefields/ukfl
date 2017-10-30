<?php


// Our custom post type function
function register_custom_posttypes() {

	$args = array(
			'labels' => array(
					'name' 					=> __( 'Teams', 'ukfl' ),
					'singular_name' 		=> __( 'Team', 'ukfl' ),
					'add_new_item' 			=> __( 'Add New Team', 'ukfl' ),
					'edit_item' 			=> __( 'Edit Team', 'ukfl' ),
					'new_item' 				=> __( 'New', 'ukfl' ),
					'view_item' 			=> __( 'View Team', 'ukfl' ),
					'search_items' 			=> __( 'Search', 'ukfl' ),
					'not_found' 			=> __( 'No results found.', 'ukfl' ),
					'not_found_in_trash' 	=> __( 'No results found.', 'ukfl' ),
					'featured_image'		=> __( 'Logo', 'ukfl' ),
					'set_featured_image' 	=> __( 'Select Logo', 'ukfl' ),
					'remove_featured_image' => __( 'Remove Logo', 'ukfl' ),
					'use_featured_image' 	=> __( 'Select Logo', 'ukfl' ),
			),
			'public' 				=> true,
			'show_ui' 				=> true,
			//'capability_type' 		=> 'ukfl_team',
			//'map_meta_cap' 			=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> false,
			'hierarchical' 			=> true,
			'rewrite' 				=> array( 'slug' => 'team' ),
			'supports' 				=> array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes', 'excerpt' ),
			'has_archive' 			=> false,
			'show_in_nav_menus' 	=> true,
			'menu_icon' 			=> 'dashicons-shield',
			//'show_in_rest' 			=> true,
			//'rest_controller_class' => 'SP_REST_Posts_Controller',
			//'rest_base' 			=> 'teams',
	);

	register_post_type('ukfl_team', $args);
}
// Hooking up our function to theme setup
add_action( 'init', 'register_custom_posttypes' );

?>