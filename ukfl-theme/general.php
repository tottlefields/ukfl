<?php

$team_types = array('league' => "League Team", 'multibreed' => 'Multibreed Team');

function ordinal($number) {
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
	if ((($number % 100) >= 11) && (($number%100) <= 13))
		return $number. 'th';
		else
			return $number. $ends[$number % 10];
}

function get_seed_time_for_team($team){
	global $wpdb;
	$result = $wpdb->get_row( "SELECT * from ukfl_event_results where team='".$team->post_title."' and team_type ='League'" );
	return $result;	
}

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

function get_sub_teams_for_team($team_id){
	global $wpdb;
	$args = array(
		'post_type'	=> 'ukfl_sub-team',
		'orderby'       =>  'post_title',
                'order'         =>  'ASC',
		'post_status'   => array('publish', 'pending'),
                'posts_per_page'=> -1,
		'post_parent'   => $team_id,

	);
	$teams = get_posts($args);

	return $teams;
}

function get_dogs_for_team($team_id){
        global $wpdb;
        $args = array(
                'post_type'     => 'ukfl_dog',
                'post_status'   => 'publish',
                'posts_per_page'=> -1,
                'post_parent'   => $team_id,
		'order'		=> 'ASC',
		'orderby'	=> 'meta_value',
		'meta_key'	=> 'ukfl_dog_name'

        );
        $dogs = get_posts($args);

        return $dogs;
}

function get_juniors_for_account(){
	global $wpdb, $current_user;
	$juniors = array();
	$junior_ids = get_user_meta( $current_user->ID, "ukfl_juniors", 1);
	if($junior_ids != ''){
		foreach (explode(',', get_user_meta( $current_user->ID, "ukfl_juniors", 1)) as $ukfl){
			$junior = get_user_by('login', $ukfl);
			array_push($juniors, $junior);
		}
	}
	return $juniors;
}

function get_dogs_for_account(){
	global $wpdb, $current_user;
	
	$authors = array($current_user->ID);
	$juniors = get_juniors_for_account();
	foreach ($juniors as $j){ array_push($authors, $j->ID); }	
	$args = array(
			//'author'        =>  $current_user->ID,
			'author__in'	=> $authors,
			'post_type'	=> 'ukfl_dog',
			'orderby'       => 'post_date',
			'order'         => 'ASC',
			'post_status'	=> array('publish'),
			'posts_per_page' => -1 // no limit
	);
	$dogs = get_posts( $args );
	return $dogs;
}

function get_ukfl_height_for_dog($dog_id){
	global $wpdb;

	$ukfl_height = $wpdb->get_var( "select measure_final from ukfl_dog_measures where dog_ukfl = '".$dog_id."'" );
	if ($ukfl_height > 0 && $ukfl_height < 12) { $ukfl_height = $ukfl_height.'"'; }
	else { $ukfl_height = 'FH'; }

	return $ukfl_height;
}

function get_events_for_teams($team_ids){
	$args = array(
			'post_type'     => 'ukfl_event',
			'post_status'	=> array('publish', 'pending'),
			'posts_per_page' => -1,
			'order'			=> 'ASC',
			'meta_key'		=> 'ukfl_event_start_date',
			'orderby'   => 'meta_value_num',
			'post_parent__in' => $team_ids,
			'meta_query' => array('key' => 'ukfl_event_start_date', 'value' => date('Ymd'), 'compare' => '>=')
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
