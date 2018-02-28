<?php

function custom_team_meta_box_markup($post) {
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	
	$teams = get_posts(
		array(
			'post_type'   => 'ukfl_team',
	        	'post_status' => array('publish', 'pending'),
			'orderby'     => 'title',
        		'order'       => 'ASC',
        		'numberposts' => -1
		)
	);
	
	if ( !empty( $teams ) ) {
		echo '<select name="parent_id" class="widefat">'; // !Important! Don't change the 'parent_id' name attribute.
		echo '<option value="0">None</option>';
		foreach ( $teams as $team ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $team->ID ), selected( $team->ID, $post->post_parent, false ), esc_html( $team->post_title ) );
		}
		echo '</select>';
	}
}

function add_sub_team_custom_meta_box($post){
    add_meta_box("sub-team-meta-box", "Parent Team", "custom_team_meta_box_markup", "ukfl_sub-team", "side", "core", null);
}

function add_dog_team_custom_meta_box($post){
    add_meta_box("dog-team-meta-box", "Team", "custom_team_meta_box_markup", "ukfl_dog", "side", "core", null);
}

function custom_sub_event_meta_box_markup($post) {
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");

        $events = get_posts(
                array(
                        'post_type'   => 'ukfl_event',
		        'post_status' => array('publish', 'pending'),
                        'orderby'     => 'title',
                	'order'       => 'ASC',
	                'numberposts' => -1
                )
        );

        if ( !empty( $events ) ) {
                echo '<select name="parent_id" class="widefat">'; // !Important! Don't change the 'parent_id' name attribute.
                echo '<option value="0">None</option>';
                foreach ( $events as $event ) {
                        printf( '<option value="%s"%s>%s</option>', esc_attr( $event->ID ), selected( $event->ID, $post->post_parent, false ), esc_html( $event->post_title ) );
                }
                echo '</select>';
        }
}

function add_sub_event_custom_meta_box($post){
    add_meta_box("sub-event-meta-box", "Event", "custom_sub_event_meta_box_markup", "ukfl_sub-event", "side", "core", null);
}


function custom_event_meta_box_markup($post){	
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");

	$postmeta = get_post_meta( $post->ID );
/*	$teams = get_posts(
                array(
                        'post_type'   => 'ukfl_team',
               		'post_status' => array('publish', 'pending'),
                        'orderby'     => 'title',
		                'order'       => 'ASC',
		                'numberposts' => -1
                )
        );

	$selected = isset( $postmeta['event_host_team'] ) ? esc_attr( $postmeta['event_host_team'][0] ) : '';

	echo '<label for="event_host_team">Host team</label>';
	echo '<select name="event_host_team">';
	printf('<option value="%s"%s>%s</option>', esc_attr( 'ukfl' ), selected( $selected, 'ukfl', false ), esc_html( 'UK Flyball League' ) );
	foreach ( $teams as $team ) {
        	printf( '<option value="%s"%s>%s</option>', esc_attr( $team->ID ), selected( $selected, $team->ID, false ), esc_html( $team->post_title ) );
        }
        echo '</select>';*/
}

function save_event_custom_meta_box($post_id){
	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'meta-box-nonce' ] ) && wp_verify_nonce( $_POST[ 'meta-box-nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) { return; }

	if( isset( $_POST['event_host_team'] ) )
		update_post_meta( $post_id, 'event_host_team', esc_attr( $_POST['event_host_team'] ) );
}

function add_event_custom_meta_box($post){
    add_meta_box("event-host-meta-box", "Host Team", "custom_team_meta_box_markup", "ukfl_event", "side", "high", null);
    //add_meta_box("event-meta-box", "Event Details", "custom_event_meta_box_markup", "ukfl_event", "normal", "high", null);
}

function custom_event_host_meta_box_markup($post){
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	
	$teams = get_posts(
		array(
			'post_type'   => 'ukfl_team',
 		        'post_status' => array('publish'),
			'orderby'     => 'title',
        		'order'       => 'ASC',
	        	'numberposts' => -1
		)
	);
	
	if ( !empty( $teams ) ) {
		echo '<select name="parent_id" class="widefat">'; // !Important! Don't change the 'parent_id' name attribute.
		echo '<option value="0">None</option>';
		foreach ( $teams as $team ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $team->ID ), selected( $team->ID, $post->post_parent, false ), esc_html( $team->post_title ) );
		}
		echo '</select>';
	}
}



//add_action("add_meta_boxes", "add_team_custom_meta_box");
add_action("add_meta_boxes", "add_sub_team_custom_meta_box");
add_action("add_meta_boxes", "add_dog_team_custom_meta_box");
add_action("add_meta_boxes", "add_event_custom_meta_box");
add_action("add_meta_boxes", "add_sub_event_custom_meta_box");

add_action("save_post", "save_event_custom_meta_box");

?>
