<?php

function get_teams_for_user(){
	global $wpdb, $current_user;
	$args = array(
			'author'        =>  $current_user->ID,
			'post_type'		=> 'ukfl_team',
			'orderby'       =>  'post_title',
			'order'         =>  'ASC',
			'post_status'	=> array('publish', 'pending'),
			'posts_per_page' => -1 // no limit
	);
	$teams = get_posts( $args );
	return $teams;
}

function get_events_for_teams($team_ids){
	$args = array(
			'post_type'     => 'ukfl_event',
			'post_status'	=> array('publish', 'pending'),
			'posts_per_page' => -1,
			'order'			=> 'ASC',
			'meta_key'		=> 'event_start_date',
			'orderby'   => 'meta_value_num',
			'meta_query' => array(
					array('key' => 'event_host_team', 'value' => $team_ids, 'compare' => 'IN'),
					array('key' => 'event_start_date', 'value' => date('Ymd'), 'compare' => '>=')
			)
	);
	$events = get_posts($args);
	return $events;
}

function get_options_for_breeds($slug, $breeds, $selected){
	$options = '';
	foreach($breeds as $id => $breed) {
		$options .= '<option value="' . $id . '" data-breed="' . $id . '" data-type="' . $slug . '"';
		if (isset($selected) && $selected > 0 && $id == $selected){
			$options .= ' selected="selected"';
		}
		$options .= '>' . $breed['name'] . '</option>';
	}
	return $options;
}
?>
