<?php
//Template Name: UKFL Account - Junior

global $wpdb, $current_user;

if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (!(current_user_can('ukfl_member'))){ wp_safe_redirect('/account/'); exit; }

if (isset($_POST['add_dog'])){
	$dog_post = array(
			'post_title'  	=> $_POST['ukfl_no'],
			'post_name'  	=> $_POST['ukfl_no'],
			'post_status' 	=> 'draft',
			'post_author' 	=> get_current_user_id(),
			'post_parent'	=> $_POST['current_team'],
			'post_type'		=> 'ukfl_dog',
			'tax_input'		=> array(
					'dog-breeds' => array(intval($_POST['breed'])),
			),
			'meta_input'   => array(
					'ukfl_dog_owner'	=> $_POST['owner_ukfl'],
					'ukfl_dog_name'		=> $_POST['dog_name'],
					'ukfl_dog_letter'	=> $_POST['dog_ukfl'],
					'ukfl_dog_dob'		=> dateToSQL($_POST['birth_date']),
					'ukfl_dog_microchip'=> $_POST['microchip'],
					'ukfl_dog_sex'		=> $_POST['sex'],
			),
	);
	$dog_id = wp_insert_post( $dog_post );

	$content = do_shortcode("[gcp_redirect_flow ref=5]");
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
		$("a.gcp_redirect_flow5")[0].click();
	 } );
</script>';
}else{
	$js_for_footer = '
<script type="text/javascript">
        jQuery(function ($) {
			$(".ukfl-datepicker").datepicker({
				format: "dd/mm/yyyy",
				weekStart: 1,
				daysOfWeekHighlighted: "0,6",
				autoclose: true,
				todayHighlight: true,
				orientation: "bottom"
			});
	 } );
</script>';
}

if(isset($_GET['edit']) && isset($_GET['dogID'])) { $TITLE = "Edit Dog"; }
else{
	$TITLE = "Add a New Dog";
	$dog_letter = generate_ukfl_dog_number($current_user->user_login);
	$ukfl_no = $current_user->user_login.$dog_letter;
}

get_header();
include(locate_template('index-bannerstrip.php'));

$breeds = get_terms('dog-breeds', array('hide_empty' => false));
$dogBreeds = array();
foreach($breeds as $b) {
	$dogBreeds[$b->term_id] = array('name' => $b->name, 'slug' => $b->slug);
}

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
							<?php if (isset($_POST['add_dog'])){ echo $content; } else {?>
							<?php the_post(); the_content(); ?>
							<div class="alert alert-info">Junior registration for under 12s if FOC, with the option of joining the Junior Award Scheme (&pound;5.00/yr). Registration for 12-16 year olds is &pound;5.00 per year (including access to the Junior Award Shceme). Please fill in the payment details requested after adding your dog to complete your dog's UKFL&copy; registration.<br/>
							Please be aware that entering the dog's microchip number is not mandatory. You can always add this at a later stage if we require it.</div>
							<form method="post" class="form form-horizontal">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="junior_name">Name</label>
									<div class="col-sm-4">
										<input type="text" name="junior_name" id="junior_name" class="input form-control" value="" />
										<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $current_user->ID; ?>" />
										<input type="hidden" name="parent_ukfl" id="parent_ukfl" value="<?php echo $current_user->user_login; ?>" />
									</div>
									<label class="col-sm-2 control-label" for="junior_dob">DOB</label>
									<div class="col-sm-2">
									        <input type="text" name="junior_dob" id="junior_dob" class="ukfl-datepicker input form-control" value="" />
									</div>
							    </div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="junior_email">Email</label>
									<div class="col-sm-6">
										<input type="text" name="junior_email" id="junior_email" class="input form-control" value="" placeholder="Optional as not all juniors will have this />
									</div>
								</div>
								<div class="form-group">
									<div class="controls">
										<input type="submit" name="register_junior" id="register_junior" value="Register Junior" class="btn btn-success btn-busiprof pull-right" />
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
						
