<?php
	if ( (isset($_POST['Submit'])) ) {
		$rs = sendNewsletter(1);
	}elseif( (isset($_POST['SubmitToTest'])) ){
		$rs = sendNewsletter(2);
	}	
	global $wpdb;
	$settings['ean_email_frequency'] = get_option('ean_email_frequency');
	$settings['ean_last_post_id'] = get_option('ean_last_post_id');
	$settings['ean_categories'] = get_option('ean_categories');
	$settings['ean_last_send_date'] = get_option('ean_last_send_date');
	$categories = explode(",",$settings['ean_categories']);
	$cat_string = '';
	foreach( $categories as $category ){
		$cat_string .= ", ".get_cat_name($category);
	}
	$args = array(
			'numberposts'     			=> -1,
			'offset'          			=> 0,
			'orderby'         			=> 'ID',
			'order'           			=> 'ASC',
			'post_type'       			=> 'post',
			'post_status'     			=> 'publish',
			'category'     				=> $settings['ean_categories']
			);
	$setting_details = array();
	if( $settings['ean_email_frequency'] == 'b' ){	
		add_filter( 'posts_where', 'filter_where' );
		$query = new WP_Query(array('category__in' => explode(',', $settings['ean_categories']), 'post_status' => 'publish'));
		foreach($query->posts as $val){
			$recent_posts[] = $val;
		}
		$setting_details['frq_title'] = '2x/month';
		$setting_details['next_date'] = '+15 day';
		remove_filter( 'posts_where', 'filter_where' );
	}else{
		if( $settings['ean_email_frequency'] == 'm' ){
			$args['monthnum'] = date('m');
			$args['year'] = date('Y');
			$setting_details['frq_title'] = 'Monthly';
			$setting_details['next_date'] = '+1 month';
		}elseif( $settings['ean_email_frequency'] == 'w' ){
			$args['w'] = date('W');
			$args['monthnum'] = date('m');
			$args['year'] = date('Y');
			$setting_details['frq_title'] = 'Weekly';
			$setting_details['next_date'] = '+1 week';
		}elseif( $settings['ean_email_frequency'] == 'y' ){
			$args['year'] = date('Y');
			$setting_details['frq_title'] = 'Yearly';
			$setting_details['next_date'] = '+1 year';
		}elseif( $settings['ean_email_frequency'] == 'd' ){
			$args['day'] = date('d');
			$args['monthnum'] = date('m');
			$args['year'] = date('Y');
			$setting_details['frq_title'] = 'Daily';
			$setting_details['next_date'] = '+1 day';
		}
		$recent_posts = get_posts($args);
	}
	if($settings['ean_last_send_date']){
		$last_date = $settings['ean_last_send_date'];
		$next_send_date = strtotime($setting_details['next_date'],strtotime($last_date));
		$next_send_date = date('d M Y H:i:s', $next_send_date);
	}
	// Check the cron is running from CPanel or Wordpress wp_event_schedule
	// 'yes' ==> 'CPanel Cron'
	// 'no' ==> 'WP Cron
	if (get_option('ean_cp_cron') == 'no') {
		$next_send_date = wp_next_scheduled('ean_cron_action');
		$next_send_date = date('d M Y H:i:s', $next_send_date);
	}
	$posts = 0;
	foreach ( $recent_posts as $key=>$val ) {
		if( $val->ID > $settings['ean_last_post_id'] ){
			$posts++;
		}
	}
	$table_name = $wpdb->prefix . "ean_newsletter";
	$subscriber_q = " SELECT * FROM ".$table_name." WHERE 1=1 ";
	$subscriber_q .= " AND status='A' AND subscription='S' ";
	$subscribers = $wpdb->get_results($subscriber_q, ARRAY_A);
	
	// Check the Link Tweets with newsletter enabled / disabled
	if ( get_option('ean_link_tweets') ) {
		$link_tweets = 'Enabled';
	} else {
		$link_tweets = 'Disabled';
	}
	
	// Check the Link Tweets with newsletter enabled / disabled
	if ( get_option('ean_add_fbfeeds') ) {
		$link_fbfeeds = 'Enabled';
	} else {
		$link_fbfeeds = 'Disabled';
	}
?>
<div class="wrap">
	<?php echo "<h2>". __( 'Newsletter Status', 'ean_status' ) . "</h2>"; ?>
	<?php if($rs) { ?>
	<div id="message" class="updated">
		<p>Done.</p>
	</div>
	<?php }else if ( !$posts ) { ?>
	<div class="error fade">
		<p>No post to send.</p>
	</div>
	<?php } ?>
	<form name="ean_status_form" method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Send Test Mail To Admin') ?>" />
		<input type="submit" name="SubmitToTest" class="button-primary" value="<?php esc_attr_e('Send Test Mail To Specified') ?>" />
	</form>
	<div class="metabox-holder">
		<div class="postbox">
			<h3>Newsletter Status</h3>
			<div class="ean_settings">
			<ul>
				<li><strong>Blog Posts to be Sent : </strong><?php echo $posts; ?></li>
				<li><strong>Categories Selected : </strong><?php echo trim($cat_string,","); ?></li>
                <li><strong>Twitter Feeds : </strong><?php echo $link_tweets; ?></li>
                <!--<li><strong>Facebook Feeds : </strong><?php //echo $link_fbfeeds; ?></li>-->
				<li><strong>Subscribers : </strong><?php echo count($subscribers); ?></li>
				<li><strong>Newsletter Frequency Set to : </strong><?php echo $setting_details['frq_title']; ?></li>
				<li><strong>Last Sent Date : </strong><?php if($settings['ean_last_send_date']) echo date('d M Y h:i:s',strtotime($settings['ean_last_send_date'])); ?></li>
				<li><strong>Next Newsletter Scheduled : </strong><?php echo $next_send_date; ?>
				<br/><br/>(NOTE: Unsubscribe will not work in this test email but will in the real one.)
				</li>
			</ul>
			</div>
		</div>
	</div>
</div>
<div>Like EAN? Give us a <a href="http://wordpress.org/extend/plugins/easy-automatic-newsletter/" target="_blank"><strong>rating</strong></a>. Need assistance or have <a href="<?php echo admin_url('admin.php').'?page=ean-admin-feedback'; ?>"><strong>suggestions</strong></a>?</div>