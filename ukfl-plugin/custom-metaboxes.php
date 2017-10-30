<?php

function custom_club_meta_box_markup($object){
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

function add_club_custom_meta_box(){
    add_meta_box("club-meta-box", "Team Details", "custom_club_meta_box_markup", "ukfl_club", "side", "default", null);
}


function custom_team_meta_box_markup($post) {
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	
	$clubs = get_posts(
		array(
			'post_type'   => 'ukfl_club',
			'orderby'     => 'title',
        	'order'       => 'ASC',
        	'numberposts' => -1
		)
	);
	
	if ( !empty( $clubs ) ) {
		echo '<select name="parent_id" class="widefat">'; // !Important! Don't change the 'parent_id' name attribute.
		echo '<option value="0">None</option>';
		foreach ( $clubs as $club ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $club->ID ), selected( $club->ID, $post->post_parent, false ), esc_html( $club->post_title ) );
		}
		echo '</select>';
	}
}

function add_team_custom_meta_box($post){
    add_meta_box("team-meta-box", "Parent Club", "custom_team_meta_box_markup", "ukfl_team", "side", "core", null);
}


add_action("add_meta_boxes", "add_club_custom_meta_box");
add_action("add_meta_boxes", "add_team_custom_meta_box");

?>
