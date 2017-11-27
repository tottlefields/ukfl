<?php

function mandate_validator($input){
        $current_user = wp_get_current_user();
        update_user_meta( $current_user->ID, 'ukfl_cgp_customer_ref', $input->links->customer);
}

function subscription_validator($input){
		wp_mail(get_option('admin_email'), 'gcp_successful_mandate_setup - subscription_validator', json_encode($input));
/*        $current_user = wp_get_current_user();
        if (preg_match('/Membership/', $input->name)){
			$current_user->add_role( 'ukfl_member' );
			add_user_meta( $current_user->ID, 'ukfl_date_joined', date('Y-m-d'), 1 );
			add_user_meta( $current_user->ID, 'ukfl_date_renewal', $input->upcoming_payments[1]->charge_date, 1 );
			add_user_meta( $current_user->ID, 'ukfl_mandate_membership', $input->links->mandate, 1 );
        }*/
}
add_action('gcp_successful_mandate_setup', 'mandate_validator');
add_action('gcp_successful_payment_plan', 'subscription_validator');


require_once '/home/ec2-user/vhosts/ukflyball.org.uk/www/wp-content/plugins/gocardless-pro/lib/gcp/vendor/autoload.php';

function _create_sandbox_client(){
	$access_token = 'sandbox_2uv9or7FYuFlMG97I_M04c4KgDRggrtrUF6cps8S';
	$client = new \GoCardlessPro\Client(array(
			'access_token' => $access_token,
			'environment'  => \GoCardlessPro\Environment::SANDBOX
	));
	return $client;
}

function cpg_ukfl_get_customer($cust_ref){
	$client = _create_sandbox_client();
	$customer = $client->customers()->get($cust_ref);
}

?>
