<?php

function custom_team_meta_box_markup($object){
wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <label for="meta-box-text">Text</label>
            <input name="meta-box-text" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-text", true); ?>">

            <br>

            <label for="meta-box-dropdown">Dropdown</label>
            <select name="meta-box-dropdown">
                <?php 
                    $option_values = array(1, 2, 3);

                    foreach($option_values as $key => $value) 
                    {
                        if($value == get_post_meta($object->ID, "meta-box-dropdown", true))
                        {
                            ?>
                                <option selected><?php echo $value; ?></option>
                            <?php    
                        }
                        else
                        {
                            ?>
                                <option><?php echo $value; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>

            <br>

            <label for="meta-box-checkbox">Check Box</label>
            <?php
                $checkbox_value = get_post_meta($object->ID, "meta-box-checkbox", true);

                if($checkbox_value == "")
                {
                    ?>
                        <input name="meta-box-checkbox" type="checkbox" value="true">
                    <?php
                }
                else if($checkbox_value == "true")
                {
                    ?>  
                        <input name="meta-box-checkbox" type="checkbox" value="true" checked>
                    <?php
                }
            ?>
        </div>
    <?php  
    
}

function add_team_custom_meta_box(){
    add_meta_box("team-meta-box", "Team Details", "custom_team_meta_box_markup", "ukfl_team", "side", "default", null);
}


function custom_sub_team_meta_box_markup($post) {
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
    add_meta_box("sub-team-meta-box", "Parent Team", "custom_sub_team_meta_box_markup", "ukfl_sub-team", "side", "core", null);
}

/*
function custom_event_meta_box_markup($post){	
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");

	$postmeta = get_post_meta( $post->ID );
	$teams = get_posts(
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
        echo '</select>';
}

function add_event_custom_meta_box($post){
    add_meta_box("event-meta-box", "Event Details", "custom_event_meta_box_markup", "ukfl_event", "normal", "high", null);
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
*/

function add_event_custom_meta_box($post){
    add_meta_box("event-meta-box", "Host Team", "custom_event_meta_box_markup", "ukfl_event", "side", "high", null);
}

function custom_event_meta_box_markup($post){
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



add_action("add_meta_boxes", "add_team_custom_meta_box");
add_action("add_meta_boxes", "add_sub_team_custom_meta_box");
add_action("add_meta_boxes", "add_event_custom_meta_box");

add_action("save_post", "save_event_custom_meta_box");

?>
