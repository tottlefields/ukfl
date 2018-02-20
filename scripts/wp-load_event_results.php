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


#Date,Team,Team_Type,Div,Seed,Position,FT,Heats

if (($handle = fopen($FILE, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	if(strtolower($data[1]) == 'team'){ continue; }
//	print_r($data);
	
	$a = array_pad($data, 8, '\N');	
	$data = $a;

	$teams = get_posts(array('name' => $data[1], 'post_type' => 'ukfl_sub-team', 'post_status' => 'publish', 'post_status' => 'publish'));
	if (!$teams){ echo "ERROR - no team found with name of $data[1]\n"; continue; }
	$team = $teams[0];

	$is_current = 0;

	if (strtolower($data[2]) == 'league' || strtolower($data[2]) == 'multi-breed'){
		$sql = 'select team,fastest_time,current_seed_time from ukfl_event_results where team="'.$data[1].'" AND team_type="'.$data[2].'" order by event_date desc limit 3;';
//		$wpdb->prepare("select team,fastest_time,current_seed_time from ukfl_event_results where team=%s AND team_type=%s order by event_date desc limit 3", $data[1], $data[2]);
		$past_results = $wpdb->get_results($sql);
		if (count($past_results) == 0){ $is_current = 1; }
		else{
			print_r($past_results);
			break;
		}

	}
	elseif($data[2] == 'NFC'){ $data[5] == '\N'; }
    	else{ echo "Team Type = ".$data[2]."\n"; }

	if ($data[4] == ''){ $data[4] = '\N';}
	if ($data[7] == ''){ $data[7] = '\N';}

	//If get here then sfe to load results!
	$wpdb->insert( 'ukfl_event_results', array(
		'event_id' => $EVENT, 
		'event_date' => $data[0], 
		'team' => $data[1], 
		'team_type' => $data[2], 
		'division' => $data[3], 
		'div_rank' => $data[4],
		'div_position' => $data[5],
		'fastest_time' => $data[6],
		'heats_won' => $data[7],
		'current_seed_time' => $is_current
	) );

    }
    fclose($handle);
}


?>
