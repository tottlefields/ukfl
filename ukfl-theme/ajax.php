<?php

add_action( 'wp_ajax_event_search', 'get_event_search' );
add_action( 'wp_ajax_nopriv_event_search', 'get_event_search' );

add_action('wp_ajax_upload_points', 'upload_points');
add_action('wp_ajax_nopriv_upload_points', 'upload_points');

function upload_points(){
	global $wpdb, $current_user;
        if (!(current_user_can('ukfl_official'))){ echo json_encode(array()); wp_die(); }

	ini_set("auto_detect_line_endings", true);

	$event_date = dateToSQL($_POST['event_date']);
	$event_id = $_POST['event'];

	$fh = fopen($_FILES['portal_file']['tmp_name'], 'rb');
	$first_line = fgets($fh);
	if (count(explode("\t", $first_line)) > 1){ echo json_encode(array('error' => "File is tab-delimited. Please re-save as comma-separated (CSV) and try uploading again.")); wp_die(); }
	else{
		while (($line = fgetcsv($fh, 1000, ',')) !== false){
		        echo json_encode(array('event_date' => $event_date, 'event_id' => $event_id, 'first_line' => $line));
	        	wp_die();
		}
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
