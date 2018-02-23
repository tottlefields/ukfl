<?php

function create_gocardless_client(){
	$live = 1;
	if ($live){ return _create_live_client(); }
	return _create_sandbox_client();
}

function _create_sandbox_client(){
	$access_token = 'sandbox_2uv9or7FYuFlMG97I_M04c4KgDRggrtrUF6cps8S';
	$client = new \GoCardlessPro\Client(array(
			'access_token' => $access_token,
			'environment'  => \GoCardlessPro\Environment::SANDBOX
	));
	return $client;
}

function _create_live_client(){
	$access_token = 'live_iA7GwR9Gtj7Abi1XRX9YVb4Lficbfy2zIpDQ27ZH';
	$client = new \GoCardlessPro\Client(array(
                        'access_token' => $access_token,
                        'environment'  => \GoCardlessPro\Environment::LIVE
        ));
        return $client;
}

function cpg_ukfl_get_rdf($rdf_ref){
	$client = create_gocardless_client();
	$rdf = $client->redirectFlows()->get($rdf_ref);
	return $rdf;
}

function cpg_ukfl_get_mandate($mandate_ref){
	$client = create_gocardless_client();
	$mandate = $client->mandates()->get($mandate_ref);
	return $mandate;
}

function cpg_ukfl_get_customer($cust_ref){
	$client = create_gocardless_client();
	$customer = $client->customers()->get($cust_ref);
	return $customer;
}


?>
