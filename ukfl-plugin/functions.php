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


// Our custom post type function
function create_posttypes() {

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
						'capability_type' 		=> 'ukfl_team',
						'map_meta_cap' 			=> true,
						'publicly_queryable' 	=> true,
						'exclude_from_search' 	=> false,
						'hierarchical' 			=> true,
						'rewrite' 				=> array( 'slug' => get_option( 'ukfl_team_slug', 'team' ) ),
						'supports' 				=> array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes', 'excerpt' ),
						'has_archive' 			=> false,
						'show_in_nav_menus' 	=> true,
						'menu_icon' 			=> 'dashicons-shield-alt',
						//'show_in_rest' 			=> true,
						//'rest_controller_class' => 'SP_REST_Posts_Controller',
						//'rest_base' 			=> 'teams',
				);

	register_post_type('ukfl_team', $args);

/*	register_taxonomy('dog-breeds', array('shows'),
			array(
					'hierarchical' => false,
					'label' => 'Dog Breeds',
					'singular_label' => 'Dog Breed',
					'rewrite' => true
			)
	);


	$args = array(
			'label' => __('Show Entries'),
			'labels' => array(
					'add_new_item' => 'Add Entry',
					'edit_item' => 'Edit Entry',
					'view_item' => 'View Entries'
			),
			'singular_label' => __('Show Entry'),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => true,
			'supports' => array('title', 'author'),
			'exclude_from_search' => true,
			'has_archive' => false,
			'menu_icon' => 'dashicons-awards',
			'show_in_nav_menus' => false
	);

	register_post_type('entries', $args);*/
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttypes' );