<?php

session_start();

require_once '/home/bitnami/include/facebook-php-sdk-v5/src/Facebook/autoload.php';
require_once 'secret.php';

$fb = new Facebook\Facebook([
                'app_id' => $FB_APP_ID,
                'app_secret' => $FB_SECRET,
                'default_graph_version' => 'v2.5',
]);

//select * from wp_postmeta where meta_key='ukfl_event_open_date' and meta_value between '20180115' and date_format(date_add('20180115', interval 7 day), '%Y%m%d');

# /usr/local/bin/wp --path=/opt/bitnami/apps/wordpress/htdocs eval-file wp-facebook.php

$today = date('Ymd');
$future = date('Ymd', strtotime("+1 week"));

$args = array (
	'post_type'     => 'ukfl_event',
	'post_status'   => array('publish'),
	'meta_key'      => 'ukfl_event_open_date',
	'orderby'       => 'post_title',
	'order'         => 'ASC',
	'numberposts'   => -1,
	'meta_query'    => array(
		array(
			'key'           => 'ukfl_event_open_date',
			'compare'       => '>=',
			'value'         => $today,
		),
		array(
			'key'           => 'ukfl_event_open_date',
			'compare'       => '<',
			'value'         => $future,
		)
	)
);

$events = get_posts($args);
if (count($events) == 0){ echo "FACEBOOK - No tournaments due for release this week\n"; exit(0); }

$weekend_dates = '';
$tourns = '';
foreach ($events as $event){
	$title = get_post_meta( $event->ID, 'ukfl_event_title', true );
	$postcode = get_post_meta( $event->ID, 'ukfl_event_postcode', true );
	if ($weekend_dates == ''){
		$start_date = DateTime::createFromFormat('Ymd', get_post_meta( $event->ID, 'ukfl_event_start_date', true ));
		if ($start_date->format('N') == 7){ $start_date->sub(new DateInterval('P1D')); }
		$weekend_dates = $start_date->format('jS M'); 
	}
	$tourns .= $title.', '.$postcode;
}
$message = '* ANNOUNCEMENT *
The following UKFL event schedules for the weekend of '.$weekend_dates.' will be available to enter via links posted on this Facebook page on Thursday (8pm). Please remember entries are dealt with on a first-come-first-served basis as per the UKFL rules.

'.$tourns;

if ($FB_TOKEN){
        $response = $fb->post(
                '/'.$FB_PAGE_ID.'/feed',
                array(
                        "message" => $message,
		),
                $FB_TOKEN
                );
        // Success
        $postId = $response->getGraphNode();
        echo "Posted with id: ".$postId."\n";
}


?>
