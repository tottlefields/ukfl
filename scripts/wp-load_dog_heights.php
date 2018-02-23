<?php

global $wpdb;

print_r($args);
if (count($args) != 3){
	echo "ERROR : you must provide this script with the follwing (in this order):-  <Heights Filename> <event id> <event date (format YYYYMMDD)>\n\n";
	exit(1);
}

$FILE	= $args[0];
$EVENT	= $args[1];
$DATE	= $args[2];

$wpdb->delete('ukfl_dog_measures', array('event_id' => $EVENT, 'event_date' => $DATE));


#Dog No,Dog Name,Height

if (($handle = fopen($FILE, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	if($data[2] == 'Height' || $data[0] == ''){ continue; }
#	print_r($data);
	
	$dogs = get_posts(array('name' => $data[0], 'post_type' => 'ukfl_dog', 'post_status' => 'publish', 'post_status' => 'publish'));
	if (!$dogs){ echo "ERROR - no dog found for UKFL number $data[0]\n"; continue; }
	$dog = $dogs[0];

	$stored_name = get_post_meta( $dog->ID, 'ukfl_dog_name', true );	
	if (strtolower(trim($data[1])) != strtolower($stored_name)){
		echo "ERROR : Dog name provided for $data[0] ($data[1]) doesn't match what is on the system ($stored_name) - please fix this and reload.\n";
		continue;
	}

	//If get here then sfe to load points!
	$wpdb->insert( 'ukfl_dog_measures', array('event_id' => $EVENT, 'event_date' => $DATE, 'dog_ukfl' => strtolower($data[0]), 'measure_final' => $data[2]) );

    }
    fclose($handle);
}


?>
