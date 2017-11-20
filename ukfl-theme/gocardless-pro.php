<?php

function mandate_validator($input){
        $current_user = wp_get_current_user();
        update_user_meta( $current_user->ID, 'ukfl_cgp_customer_ref', $input->links->mandate);
}

function subscription_validator($input){
//      wp_mail(get_option('admin_email'), 'gcp_successful_mandate_setup example', json_encode($input));
        $current_user = wp_get_current_user();
        if (preg_match('/Membership/', $input->name)){
			$current_user->add_role( 'ukfl_member' );
			add_user_meta( $current_user->ID, 'ukfl_date_joined', $input->start_date, 1 );
			add_user_meta( $current_user->ID, 'ukfl_date_renewal', $input->upcoming_payments[1]->charge_date, 1 );
			add_user_meta( $current_user->ID, 'ukfl_mandate_membership', $input->links->mandate, 1 );
        }
}
add_action('gcp_successful_mandate_setup', 'mandate_validator');
add_action('gcp_successful_payment_plan', 'subscription_validator');

?>
