<?php

$team_types = array('league' => "League Team", 'multibreed' => 'Multibreed Team');

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

function get_dogs_for_account(){
	global $wpdb, $current_user;
	$args = array(
			'author'        =>  $current_user->ID,
			'post_type'		=> 'ukfl_dog',
			'orderby'       =>  'post_date',
			'order'         =>  'ASC',
			'post_status'	=> array('publish'),
			'posts_per_page' => -1 // no limit
	);
	$dogs = get_posts( $args );
	return $dogs;
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

function get_club_dropdown_menu($name="current_club", $option0="Select Current Club...", $selected) {
	global$wpdb;
	$options = '';	
	
	$clubs = get_posts(array(
			'post_type'		=> 'ukfl_team',
			'orderby'       =>  'post_title',
			'order'         =>  'ASC',
			'post_status'	=> array('publish', 'pending'),
			'posts_per_page' => -1 // no limit
	));
	foreach($clubs as $club) {
		$options .= '<option value="' . $club->ID . '"';
		if (isset($selected) && $selected > 0 && $club->ID == $selected){
			$options .= ' selected="selected"';
		}
		$options .= '>' . $club->post_title . '</option>';	
	}
	
	return '
	<select name="'.$name.'" class="form-control">
		<option value="0">'.$option0.'</option>
		'.$options.'
	</select>';
}
?>
