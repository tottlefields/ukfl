<?php

function mandate_validator($input){
        //$current_user = wp_get_current_user();
	if (preg_match('/Membership/', $input->description)){
		$customer = cpg_ukfl_get_customer($input->links->customer);
		$user = create_ukfl_member($customer->given_name, $customer->family_name, $customer->email);
//		wp_mail(get_option('admin_email'), 'gcp_successful_mandate_setup - mandate_validator', json_encode($customer));
		add_user_meta( $user->ID, 'ukfl_date_joined', date('Y-m-d'), 1 );
                add_user_meta( $user->ID, 'ukfl_date_renewal', strtotime(date("Y-m-d"). " +1 year"), 1 );
                add_user_meta( $user->ID, 'ukfl_mandate_membership', $input->links->mandate, 1 );
                add_user_meta( $user->ID, 'ukfl_customer_membership', $customer->id, 1 );
                add_user_meta( $user->ID, 'ukfl_membership_type', $input->description, 1 );
                return;
	}
}

function subscription_validator($input){
	//wp_mail(get_option('admin_email'), 'gcp_successful_mandate_setup - subscription_validator', $input->description);
        /*if (property_exists($input, 'name') && preg_match('/Membership/', $input->name)){
        	$mandate = cpg_ukfl_get_mandate($input->links->mandate);
        	$customer = cpg_ukfl_get_customer($mandate->links->customer);
        	$user = create_ukfl_member($customer->given_name, $customer->family_name, $customer->email);
			add_user_meta( $user->ID, 'ukfl_date_joined', date('Y-m-d'), 1 );
			//add_user_meta( $user->ID, 'ukfl_date_renewal', $input->upcoming_payments[1]->charge_date, 1 );
			add_user_meta( $user->ID, 'ukfl_date_renewal', strtotime(date("Y-m-d"). " +1 year"), 1 );
			add_user_meta( $user->ID, 'ukfl_mandate_membership', $input->links->mandate, 1 );
			add_user_meta( $user->ID, 'ukfl_customer_membership', $customer->id, 1 );
			add_user_meta( $user->ID, 'ukfl_membership_type', $input->name, 1 );
			return;
        }*/
        
        if (property_exists($input, 'name') && preg_match('/Team/', $input->name)){
        	$current_user = wp_get_current_user();
        	$args = array(
        			'author'        =>  $current_user->ID,
        			'post_type'	=> 'ukfl_team',
        			'orderby'       =>  'ID',
        			'order'         =>  'DESC',
        			'posts_per_page' => 1,
				'post_status'   => 'pending'
        	);
        	$teams = get_posts( $args );
        	$team = $teams[0];
        	add_post_meta( $team->ID, 'ukfl_mandate_team', $input->links->mandate, 1 );
        	return;
        }
       
        if (property_exists($input, 'description') && preg_match('/Event/', $input->description)){
        	$current_user = wp_get_current_user();
        	$args = array(
        			'author'        =>  $current_user->ID,
        			'post_type'		=> 'ukfl_event',
        			'orderby'       =>  'ID',
        			'order'         =>  'DESC',
        			'posts_per_page' => 1,
				'post_status'	=> 'pending'
        	);
        	$events = get_posts( $args );
        	$event = $events[0];
        	add_post_meta( $event->ID, 'ukfl_payment_event', $input->id, 1 );
        	return;
        }
        
        if (property_exists($input, 'name') && preg_match('/Dog/', $input->name)){
        	$current_user = wp_get_current_user();
        	$args = array(
        			'author'        =>  $current_user->ID,
        			'post_type'		=> 'ukfl_dog',
        			'orderby'       =>  'ID',
        			'order'         =>  'DESC',
        			'posts_per_page' => 1,
        	);
        	$dogs = get_posts( $args );
        	$dog = $dogs[0];
        	add_post_meta( $dog->ID, 'ukfl_mandate_dog', $input->links->mandate, 1 );
        	return;
        }
}

add_action('gcp_successful_mandate_setup', 'mandate_validator');
add_action('gcp_successful_payment_plan', 'subscription_validator');

?>
