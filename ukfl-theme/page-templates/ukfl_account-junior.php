<?php
//Template Name: UKFL Account - Junior

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

if (isset($_REQUEST['add_junior'])){
	
	$junior = create_ukfl_member($_REQUEST['first_name'], $_REQUEST['last_name'], $_REQUEST['junior_email']);
	add_user_meta( $junior->ID, 'ukfl_date_joined', date('Y-m-d'), 1 );
	//add_user_meta( $user->ID, 'ukfl_mandate_membership', $rdf->links->mandate, 1 );
	add_user_meta( $junior->ID, 'ukfl_date_renewal', strtotime(date("Y-m-d"). " +1 year"), 1 );
	add_user_meta( $junior->ID, 'ukfl_membership_type', 'Membership - Junior', 1 );
	add_user_meta( $junior->ID, 'ukfl_parent_no', $_REQUEST['parent_ukfl'], 1 );
	add_user_meta( $junior->ID, 'ukfl_junior_dob', dateToSQL($_REQUEST['junior_dob']), 1 );
	$junior->add_role('ukfl_junior');
	
	$juniors_for_user = get_user_meta( $current_user->ID, "ukfl_juniors", 1);
	$juniors_for_user .= ",".$junior->user_login;
	if(substr($juniors_for_user,0,1) == ",") { $juniors_for_user = substr($juniors_for_user,1,strlen($juniors_for_user)-1); }
	update_user_meta($current_user->ID, "ukfl_juniors", $juniors_for_user);
	
	$updated_junior = get_user_by('id', $junior->ID);
	$dob = DateTime::createFromFormat('Ymd', get_user_meta( $updated_junior->ID, "ukfl_junior_dob", 1));

	$admin_msg = 'New junior registration on '.get_bloginfo('name').':<br /><br />
	Parent: <strong>'.$current_user->user_firstname.' '.$current_user->user_lastname.'</strong><br />
	Junior: <strong>'.$updated_junior->display_name.'</strong><br />
	UKFL Number: <strong>'.$updated_junior->user_login.'</strong><br />
	DOB: <strong>'.$dob->format('jS M Y').'</strong><br /><br />';
	if ($_REQUEST['junior_scheme'] == 'on' || $_REQUEST['junior_age'] >= 12){ $admin_msg .= 'Junior Award Scheme: <strong>YES</strong>'; }
	else{ $admin_msg .= 'Junior Award Scheme: <strong>NO</strong>'; }
	$headers = array('Content-Type: text/html; charset=UTF-8', 'Cc:'.get_option('admin_email'));
	wp_mail('juniorliaison@ukflyball.org.uk', '['.get_bloginfo('name').'] New Junior Registration', $admin_msg, $headers);
	
	if ($_REQUEST['junior_scheme'] == 'on' || $_REQUEST['junior_age'] >= 12){
		add_user_meta( $junior->ID, 'ukfl_junior_scheme', 'yes', 1 );
		add_user_meta( $current_user->ID, 'ukfl_junior_scheme', $junior->user_login, 1 );

		$content = do_shortcode("[gcp_redirect_flow ref=3]");
		$js_for_footer = '
	<script type="text/javascript">
	        jQuery(function ($) {
			$("a.gcp_redirect_flow3")[0].click();
		 } );
	</script>';
	}else{
		wp_safe_redirect('/account/');
		exit;
	}
}else{
	$js_for_footer = '
<script type="text/javascript">
	function dayOfYear(d){
		var y = d.getFullYear();
		var leapY = (y % 100 === 0) ? (y % 400 === 0) : (y % 4 === 0);
		var jan1st = new Date(y, 0, 1, 12, 0, 0, 0);
		d.setHours(12,0,0,0);
		var dayOfYear = Math.abs(d.getTime() - jan1st.getTime());
		if (dayOfYear >= 5097600000 && leapY){ dayOfYear -= (60*60*24*1000); }
		return dayOfYear;
	}

        jQuery(function ($) {
			$.validate();
			
			var today = new Date();
			today.setHours(0,0,0,0);
			var pastDate = new Date();
			pastDate.setFullYear(new Date().getFullYear() - 16);
			pastDate.setDate(new Date().getDate() + 1);
			var startDate = pastDate.getDate() + "/" + (pastDate.getMonth()+1) + "/" + pastDate.getFullYear();
			var endDate = today.getDate() + "/" + (today.getMonth()+1) + "/" + today.getFullYear();
			
			$(".ukfl-datepicker").datepicker({
				format: "dd/mm/yyyy",
				startDate: startDate,
				endDate: endDate,
			maxViewMode: 2,
    				startView: 2,
				weekStart: 1,
				daysOfWeekHighlighted: "0,6",
				autoclose: true,
				todayHighlight: true,
//				orientation: "bottom auto"
			});
			$("#junior_dob").datepicker().on("changeDate", function(e) {
        			// `e` here contains the extra attributes
					$("#junior_scheme").bootstrapToggle("enable");
					$("#junior_scheme").bootstrapToggle("off");
					var y1 = today.getFullYear();
					var y2 = e.date.getFullYear();
					var juniorAge = y1 - y2;
					x1 = dayOfYear(today);
					x2 = dayOfYear(e.date);
					if (x1 - x2 < 0) juniorAge--;
					//console.log(juniorAge);
					$("#junior_age").val(juniorAge);
					if (juniorAge >= 16){ console.log("ERROR : Junior is over 16 yeard old and requires their own individual membership"); }
					else if (juniorAge >= 12){ 
						//console.log("Junior is aged 12 or over and is required to join junior award scheme");
						$("#junior_scheme").bootstrapToggle("on");
						$("#junior_scheme").bootstrapToggle("disable");
					}
    		});
	 } );
</script>';
}

if(isset($_GET['edit']) && isset($_GET['dogID'])) { $TITLE = "Edit Dog"; }
else{
	$TITLE = "Junior UKFL Registration";
}

get_header();
include(locate_template('index-bannerstrip.php'));

?>
<!-- Blog & Sidebar Section -->
<section>		
	<div class="container">
		<div class="row">
			<!--Blog Posts-->
			<div class="col-md-12 col-xs-12">
				<div class="page-content">
					<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?> > 					
						<div class="entry-content">
							<?php if (isset($_POST['add_junior'])){ echo $content; } else {?>
							<?php the_post(); the_content(); ?>
							<div class="alert alert-info">Registration for juniors under 12 is free of charge, with the option of joining the Junior Award Scheme (&pound;5.00/yr).<br />
Registration for 12-16 year olds is &pound;5.00 per year (including access to the Junior Award Shceme).<br/>
							</div>
							<form method="post" class="form form-horizontal">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="first_name">Name</label>
									<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $current_user->ID; ?>" />
									<input type="hidden" name="parent_ukfl" id="parent_ukfl" value="<?php echo $current_user->user_login; ?>" />
									<div class="col-sm-3">
										<input type="text" placeholder="First Name" name="first_name" id="first_name" class="input form-control" value="" >
									</div>
									<div class="col-sm-3">
										<input type="text" placeholder="Last Name" name="last_name" id="last_name" class="input form-control" value="">
									</div>
									<label class="col-sm-2 control-label" for="junior_dob">DOB</label>
									<div class="col-sm-2">
									        <input type="text" name="junior_dob" id="junior_dob" class="ukfl-datepicker input form-control" value="" placeholder="dd/mm/yyyy" />
									</div>
							    </div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="junior_email">Email</label>
									<div class="col-sm-6">
										<input type="text" name="junior_email" id="junior_email" class="input form-control" value="" placeholder="Optional as not all juniors will have this" />
									</div>
									<label class="col-sm-2 control-label" for="junior_dob">Age </label>
									<div class="col-sm-2">
									        <input type="text" name="junior_age" id="junior_age" class="input form-control" value="" readonly />
									</div>
								</div>
								
								<div class="form_group">
									<div class="col-sm-2 control-label">
										<input type="checkbox" data-toggle="toggle" data-on="Yes" data-off="No" data-size="small" name="junior_scheme" id="junior_scheme"  />
									</div>
									<div class="col-sm-10">
										<label>I would like to take part in the UKFL&copy; Junior Award Scheme 
										<small style="font-weight:normal;font-style:italic;">(ticking this box will forward you to another GoCardless direct debit payment page).</small></label>
									</div>
								</div>
								<div class="form-group">
									<div class="controls">
										<input type="submit" name="add_junior" id="add_junior" value="Add Junior" class="btn btn-success btn-busiprof pull-right" />
									</div>
								</div>    
							</form>
							<?php } ?>
						</div>
					</article>
				</div>
			</div>
			<!--/End of Blog Posts-->
		</div>	
	</div>
</section>
<!-- End of Blog & Sidebar Section -->
 
<div class="clearfix"></div>
<?php get_footer(); ?>
<?php echo $js_for_footer; ?>
						
