<?php

function _create_sandbox_client(){
	$access_token = 'sandbox_2uv9or7FYuFlMG97I_M04c4KgDRggrtrUF6cps8S';
	$client = new \GoCardlessPro\Client(array(
			'access_token' => $access_token,
			'environment'  => \GoCardlessPro\Environment::SANDBOX
	));
	return $client;
}

function cpg_ukfl_get_mandate($mandate_ref){
	$client = _create_sandbox_client();
	$mandate = $client->mandates()->get($mandate_ref);
	return $mandate;
}

function cpg_ukfl_get_customer($cust_ref){
	$client = _create_sandbox_client();
	$customer = $client->customers()->get($cust_ref);
	return $customer;
}


?>
