<?php

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
debug_array($events);

?>
