<?php

global $wpdb;

print_r($args);
if (count($args) != 2){
	echo "ERROR : you must provide this script with the following (in this order):-  <Results Filename> <event/sub-event id>\n\n";
	exit(1);
}

$FILE	= $args[0];
$EVENT	= $args[1];
//$DATE	= $args[2];

$dates_seen = array();

#Date,Team,Team_Type,Div,Seed,Position,FT,Heats
if (($handle = fopen($FILE, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	if(strtolower($data[1]) == 'team'){ continue; }
//	print_r($data);
	
	if (!in_array($data[0], $dates_seen)){
		$wpdb->delete('ukfl_event_results', array('event_id' => $EVENT, 'event_date' => $data[0]));
		array_push($dates_seen, $data[0]);
	}

	$a = array_pad($data, 8, '\N');	
	$data = $a;

	$teams = get_posts(array('name' => $data[1], 'post_type' => 'ukfl_sub-team', 'post_status' => 'publish', 'post_status' => 'publish'));
	if (!$teams){ echo "ERROR - no team found with name of $data[1]\n"; continue; }
	$team = $teams[0];

/*	if (strtolower($data[2]) == 'league' || strtolower($data[2]) == 'multi-breed'){
		$sql = 'select ID,team,fastest_time,current_seed_time from ukfl_event_results where team="'.$data[1].'" AND team_type="'.$data[2].'" order by event_date desc limit 3;';
//		$wpdb->prepare("select team,fastest_time,current_seed_time from ukfl_event_results where team=%s AND team_type=%s order by event_date desc limit 3", $data[1], $data[2]);
		$past_results = $wpdb->get_results($sql);
		if (count($past_results) == 0){ $is_current = 1; }
		else{
			if (count($past_results) == 3){
				$update_sql = 'update ukfl_event_results set current_seed_time=0 where ID='.$past_results[0]->ID;
				echo $update_sql;
				$tmp_array = array_shift($past_results);
				$past_results = $tmp_array;
			}
			foreach ($past_results as $result){
				if ($result->current_seed_time){
					if ($result->fastest_time > $data[6]){
						$is_current = 1;
						$wpdb->update( 'ukfl_event_results', array('current_seed_time' => 0), array('ID' => $result->ID));
					}
				}
			}
		}

	} */
	if(strtolower($data[2]) == 'nfc'){ $data[5] == '\N'; }
    	else{ echo "Team Type = ".$data[2]."\n"; }

	if ($data[4] == ''){ $data[4] = '\N';}
	if ($data[7] == ''){ $data[7] = '\N';}

	//If get here then sfe to load results!
	$wpdb->insert( 'ukfl_event_results', array(
		'event_id' => $EVENT, 
		'event_date' => $data[0], 
		'team' => strtolower($data[1]), 
		'team_type' => $data[2], 
		'division' => $data[3], 
		'div_rank' => $data[4],
		'div_position' => $data[5],
		'fastest_time' => $data[6],
		'heats_won' => $data[7],
	) );
	$fastest_id = $wpdb->insert_id;
	$fastest_time = $data[6];

	if (strtolower($data[2]) == 'league' || strtolower($data[2]) == 'multi-breed'){
		$wpdb->update( 'ukfl_event_results', array('current_seed_time' => 0), array('team' => strtolower($data[1]), 'team_type' => $data[2]));
		$sql = 'select ID,team,fastest_time,current_seed_time from ukfl_event_results where team="'.$data[1].'" AND team_type="'.$data[2].'" order by event_date asc limit 3;';
		$past_results = $wpdb->get_results($sql);
		foreach ($past_results as $result){
			if ($result->fastest_time > 0 && $result->fastest_time <= $fastest_time){
				$fastest_time = $result->fastest_time;
				$fastest_id = $result->ID;
			}
		}
		$wpdb->update( 'ukfl_event_results', array('current_seed_time' => 1), array('ID' => $fastest_id));
	}
    }
    fclose($handle);
}


?>
