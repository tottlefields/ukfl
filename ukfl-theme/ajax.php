<?php

add_action( 'wp_ajax_event_search', 'get_event_search' );
add_action( 'wp_ajax_nopriv_event_search', 'get_event_search' );

add_action('wp_ajax_upload_points', 'upload_points');
add_action('wp_ajax_nopriv_upload_points', 'upload_points');

function upload_points(){
	global $wpdb, $current_user;
        if (!(current_user_can('ukfl_official'))){ echo json_encode(array()); wp_die(); }

	ini_set("auto_detect_line_endings", true);

	$DATE = dateToSQL($_POST['event_date']);
	$EVENT = $_POST['event'];

	$fh = fopen($_FILES['portal_file']['tmp_name'], 'rb');
	$first_line = fgets($fh);
	if (count(explode("\t", $first_line)) > 1){ echo json_encode(array('error' => "File is tab-delimited. Please re-save as comma-separated (CSV) and try uploading again.")); wp_die(); }
	else{
		$wpdb->delete('ukfl_dog_points', array('event_id' => $EVENT, 'event_date' => $DATE));
		$errors = array();
		$warnings = array();
		$success_count = 0;
    	while (($data = fgetcsv($fh, 1000, ",")) !== FALSE) {
	        //echo json_encode(array('event_date' => $event_date, 'event_id' => $event_id, 'first_line' => $line));
	        if($data[3] == 'points' || $data[0] == ''){ continue; }
	        
			$dogs = get_posts(array('name' => $data[0], 'post_type' => 'ukfl_dog', 'post_status' => 'publish', 'post_status' => 'publish'));
			if (!$dogs){ array_push($errors, "ERROR - no dog found for UKFL number $data[0]"); continue; }
			$dog = $dogs[0];

			$teams = get_posts(array('name' => $data[1], 'post_type' => 'ukfl_sub-team', 'post_status' => 'publish', 'post_status' => 'publish'));
			if (!$teams){ array_push($errors, "ERROR - no team found with name of $data[1]"); continue; }
			$team = $teams[0];

			if ($data[2] == 'League'){

				if ($team->post_parent != $dog->post_parent || $dog->post_parent == 0){
					$team_date = DateTime::createFromFormat('Ymd', get_post_meta( $dog->ID, 'ukfl_team_joined', true ));
					if($team_date){ 	//check 90 day period for a dog held to a team
						array_push($warnings, "TODO - need to check old ukfl_team_joined date for dog number $data[0]");
						continue;
					}

					array_push($warnings, "NOTE - dog $data[0] assigned to new team - ".get_the_title( $team->post_parent ));
					update_post_meta($dog->ID, 'ukfl_team_joined', $DATE);
					$wpdb->update( $wpdb->posts, array('post_parent' => $team->post_parent), array('ID' => $dog->ID) );
				}
				else{
					$team_date = DateTime::createFromFormat('Ymd', get_post_meta( $dog->ID, 'ukfl_team_joined', true ));
	                if(!$team_date){
						// dog hasn't raced yet at all, set first date racing with team
						update_post_meta($dog->ID, 'ukfl_team_joined', $DATE);
	                }					
				}				
			}
//	    	else{ echo "Team Type = ".$data[2]."\n"; }

			//If get here then safe to load points!
			$wpdb->insert( 'ukfl_dog_points', array('event_id' => $EVENT, 'event_date' => $DATE, 'team' => $data[1], 'team_type' => $data[2], 'dog_ukfl' => strtolower($data[0]), 'points' => $data[3]) );
			$success_count++;
    	}

    	fclose($fh);
    	
 		$return = array();
 		if(count($errors) > 0){ $return['errors'] = $errors; }
 		if(count($warnings) > 0){ $return['warnings'] = $warnings; }
 		$return['success'] = $success_count;
    	
 		echo json_encode($return);
	    wp_die();
	}
}


function get_event_search(){
	global $wpdb, $current_user;
	if (!(current_user_can('ukfl_official'))){ echo json_encode(array()); wp_die(); }

	$event_details = array();

	$events = get_posts(
		array(
                        'post_type'   => 'ukfl_event',
		        'post_status' => array('publish'),
                        'orderby'     => 'title',
                	'order'       => 'ASC',
	                'numberposts' => -1,
			'meta_query' 	=> array(
				array('key' => 'ukfl_event_start_date', 'value' => $_POST['meta_value'], 'compare' => '<='),
				array('key' => 'ukfl_event_end_date', 'value' => $_POST['meta_value'], 'compare' => '>=')
			)
                )
	);

	foreach ($events as $event){
		$event_parts = get_posts( array('post_parent' => $event->ID, 'post_type' => 'ukfl_sub-event'));
		$start_date = DateTime::createFromFormat('Ymd', get_post_meta( $event->ID, 'ukfl_event_start_date', true ));
		$e_title = get_post_meta($event->ID, 'ukfl_event_title', true);
		if (count($event_parts) > 0){
			foreach ($event_parts as $p){
				$ep_title = get_the_title( $p->ID);
				//array_push($event_details, array('event_id' => $p->ID, 'event_title' => $e_title.' ('.$start_date->format('jS M').') - '.$ep_title));
				array_push($event_details, array('event_id' => $p->ID, 'event_title' => $e_title.' ('.$ep_title.')'));
			}
		}
		else{
			array_push($event_details, array('event_id' => $event->ID, 'event_title' => $e_title));
		}
	}

	echo json_encode($event_details);
	wp_die();

}



?>
