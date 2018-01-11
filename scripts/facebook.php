<?php

session_start();

require_once '/home/bitnami/include/facebook-php-sdk-v5/src/Facebook/autoload.php';
require_once 'secret.php';

$fb = new Facebook\Facebook([
                'app_id' => $FB_APP_ID,
                'app_secret' => $FB_SECRET,
                'default_graph_version' => 'v2.5',
]);

$message = "This is a test post from the ukflyball.org.uk website done at 16:21 on Tuesday 9th Jan 2018.";
$url = "https://www.ukflyball.org.uk/diary";
//$image = "https://www.iball4flyball.co.uk/images/teams/".$tournament['host_trn'].".png";
$title = "TEST";

if ($FB_TOKEN){
	$response = $fb->post(
		'/'.$FB_PAGE_ID.'/feed',
		array(
			"message" => $message,
//			"link" => $url,
//			"picture" => $image,
//			"name" => $title,
//			"caption" => "www.ukflyball.org.uk",
//			"description" => $message
			),
		$FB_TOKEN
		);
	// Success
	$postId = $response->getGraphNode();
	echo "Posted with id: ".$postId."\n";
}

?>
