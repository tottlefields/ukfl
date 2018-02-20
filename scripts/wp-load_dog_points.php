<?php

global $wpdb;

print_r($args);
if (count($args) != 3){
	echo "ERROR : you must provide this script with the follwing (in this order):-  <Points Filename> <event id> <event date (format YYYYMMDD)>\n\n";
	exit(1);
}

$FILE	= $args[0];
$EVENT	= $args[1];
$DATE	= $args[2];


#Dog No.	Team	Type_Typ	points

if (($handle = fopen($FILE, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	if($data[3] == 'points' || $data[0] == ''){ continue; }
#	print_r($data);
	
	$dogs = get_posts(array('name' => $data[0], 'post_type' => 'ukfl_dog', 'post_status' => 'publish', 'post_status' => 'publish'));
	if (!$dogs){ echo "ERROR - no dog found for UKFL number $data[0]\n"; continue; }
	$dog = $dogs[0];
	
	$teams = get_posts(array('name' => $data[1], 'post_type' => 'ukfl_sub-team', 'post_status' => 'publish', 'post_status' => 'publish'));
	if (!$teams){ echo "ERROR - no team found with name of $data[1]\n"; continue; }
	$team = $teams[0];

#	echo $data[0]."\t".$dog->post_parent."\t".$team->ID."\n";	
#	print_r($dog);
#	print_r($team);
	
	if ($data[2] == 'League'){

		if ($team->post_parent != $dog->post_parent || $dog->post_parent == 0){
			$team_date = DateTime::createFromFormat('Ymd', get_post_meta( $dog->ID, 'ukfl_team_joined', true ));
			if($team_date){ 	//check 90 day period for a dog held to a team
				echo "ERROR - need to check old ukfl_team_joined date for dog number $data[0]\n";
				continue;	
			}

			echo "dog $data[0] needs assigning to team ".get_the_title( $team->post_parent )."\n";
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
//    	else{ echo "Team Type = ".$data[2]."\n"; }

	//If get here then safe to load points!
	$wpdb->insert( 'ukfl_dog_points', array('event_id' => $EVENT, 'event_date' => $DATE, 'team' => $data[1], 'team_type' => $data[2], 'dog_ukfl' => strtolower($data[0]), 'points' => $data[3]) );

    }
    fclose($handle);
}


?>
