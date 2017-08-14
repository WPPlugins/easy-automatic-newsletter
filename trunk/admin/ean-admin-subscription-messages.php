<?php
	global $wpdb;
	if ( (isset($_POST['submitSubMail'])) ) {
		update_option('ean_subscription_mail_subject', $_POST['ean_subscription_mail_subject']);
		update_option('ean_subscription_mail_msg', $_POST['ean_subscription_mail_msg']);
		$rs = 1;
	}elseif ( (isset($_POST['submitConfirmMsg'])) ) {
		update_option('ean_confirmation_msg', $_POST['ean_confirmation_msg']);
		$rs = 1;
	}elseif ( (isset($_POST['submitUnsubMsg'])) ) {
		update_option('ean_unsubscribed_msg', $_POST['ean_unsubscribed_msg']);
		$rs = 1;
	}elseif ( (isset($_POST['submitUnsubMail'])) ) {
		update_option('ean_unsubscribed_mail_subject', $_POST['ean_unsubscribed_mail_subject']);
		update_option('ean_unsubscribed_mail_msg', $_POST['ean_unsubscribed_mail_msg']);
		$rs = 1;
	}elseif ( (isset($_POST['submitEmailTitle'])) ) {
		update_option('ean_newsletter_today_title', $_POST['ean_newsletter_today_title']);
		update_option('ean_newsletter_weekly_title', $_POST['ean_newsletter_weekly_title']);
		update_option('ean_newsletter_monthly_title', $_POST['ean_newsletter_monthly_title']);
		update_option('ean_newsletter_yearly_title', $_POST['ean_newsletter_yearly_title']);
		update_option('ean_newsletter_detailed_title', $_POST['ean_newsletter_detailed_title']);
		$rs = 1;
	}
	

	$_POST['ean_subscription_mail_subject'] = stripslashes(get_option('ean_subscription_mail_subject'));
	$_POST['ean_subscription_mail_msg'] = stripslashes(get_option('ean_subscription_mail_msg'));
	$_POST['ean_confirmation_msg'] = stripslashes(get_option('ean_confirmation_msg'));
	$_POST['ean_unsubscribed_msg'] = stripslashes(get_option('ean_unsubscribed_msg'));
	$_POST['ean_unsubscribed_mail_subject'] = stripslashes(get_option('ean_unsubscribed_mail_subject'));
	$_POST['ean_unsubscribed_mail_msg'] = stripslashes(get_option('ean_unsubscribed_mail_msg'));
	$_POST['ean_newsletter_today_title'] = stripslashes(get_option('ean_newsletter_today_title'));
	$_POST['ean_newsletter_weekly_title'] = stripslashes(get_option('ean_newsletter_weekly_title'));
	$_POST['ean_newsletter_monthly_title'] = stripslashes(get_option('ean_newsletter_monthly_title'));
	$_POST['ean_newsletter_yearly_title'] = stripslashes(get_option('ean_newsletter_yearly_title'));
	$_POST['ean_newsletter_detailed_title'] = stripslashes(get_option('ean_newsletter_detailed_title'));
?>

<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/easy-automatic-newsletter/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		editor_selector : "tmcEditor",
		theme : "advanced",
		relative_urls : false,
		remove_script_host : false,
		theme_advanced_buttons3: "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_resizing : true,
		theme_advanced_statusbar_location: "bottom",
		document_base_url : "<?php echo get_option('home'); ?>/",
		force_p_newlines : false,
		force_br_newlines : true
	});
</script>
<div class="wrap">
	<?php echo "<h2>". __( 'Subscription Messages', 'Subscription Messages' ) . "</h2>"; ?>
	<?php if($rs) { ?>
	<div id="message" class="updated">
		<p>Done.</p>
	</div>
	<?php } ?>
	<p>
		<h3>Shortcodes</h3>
		<ul>
			<li><strong>{confirm_url}</strong> : Confirmation link</li>
			<li><strong>{unsub_link}</strong> : Unsubscribe link</li>
			<li><strong>{user_email}</strong> : User's email</li>
			<li><strong>{site_name}</strong> : Site name </li>
			<li><strong>{site_url}</strong> : Site url</li>
			<li><strong>{admin_email}</strong> : Admin's email</li>
			<li><strong>{next_send_date}</strong> : Next newsletter scheduled date</li>
		</ul>
	</p>
	<div class="metabox-holder">
		<div class="postbox">
			<form method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">
				<h3>Opt-In E-Mail Configuration</h3>
				<div class="ean_settings">
					<p><strong>Configure the confirmation email below. Don't forget to click Save after making changes.</strong></p>
					
					<label for="ean_subscription_mail_subject"><?php _e("Subject"); ?></label>
					<input type="text" name="ean_subscription_mail_subject" value="<?php echo $_POST['ean_subscription_mail_subject']; ?>" size="150">

					
					<label for="ean_subscription_mail_msg"><?php _e("Message"); ?></label>
					<textarea name="ean_subscription_mail_msg" cols="152" rows="20" class="tmcEditor"><?php echo $_POST['ean_subscription_mail_msg']; ?></textarea>
					<br/>
					<input type="submit" name="submitSubMail" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
				</div>
			</form>
		</div>
		<div class="postbox">
			<form method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">
				<h3>Confirmation Message</h3>
				<div class="ean_settings">
					<p><strong>This is page and message seen after a subscriber clicks the confirmation link.</strong></p>
					

					<label for="ean_confirmation_msg"><?php _e("Message"); ?></label>
					<textarea name="ean_confirmation_msg" cols="152" rows="15" class="tmcEditor"><?php echo $_POST['ean_confirmation_msg']; ?></textarea>
					<br/>
					<input type="submit" name="submitConfirmMsg" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
				</div>
			</form>
		</div>
		<div class="postbox">
			<form method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">
				<h3>Unsubscribed Message</h3>
				<div class="ean_settings">
					<p><strong>The text to show when user click on unsubscription link.</strong></p>
					
					<label for="ean_unsubscribed_msg"><?php _e("Message"); ?></label>
					<textarea name="ean_unsubscribed_msg" cols="152" rows="15" class="tmcEditor"><?php echo $_POST['ean_unsubscribed_msg']; ?></textarea>
					<br/>
					<input type="submit" name="submitUnsubMsg" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
				</div>
			</form>
		</div>
		<div class="postbox">
			<form method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">
				<h3>Unsubscribed Email</h3>
				<div class="ean_settings">
					<p><strong>To send an email after unsubscribing.</strong></p>
					

					<label for="ean_unsubscribed_mail_subject"><?php _e("Subject"); ?></label>
					<input type="text" name="ean_unsubscribed_mail_subject" value="<?php echo $_POST['ean_unsubscribed_mail_subject']; ?>" size="150">
			

					<label for="ean_unsubscribed_mail_msg"><?php _e("Message"); ?></label>
					<textarea name="ean_unsubscribed_mail_msg" cols="152" rows="20" class="tmcEditor"><?php echo $_POST['ean_unsubscribed_mail_msg']; ?></textarea>
					<br/>
					<input type="submit" name="submitUnsubMail" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
				</div>
			</form>
		</div>
        <div class="postbox">
        	<form method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>"><em></em>
            	<h3>Newsletter Titles</h3>
                <div class="ean_settings">
	                <label for="ean_newsletter_today_title"><?php _e("Today Stories Title"); ?></label>
	                <input type="text" name="ean_newsletter_today_title" value="<?php echo $_POST['ean_newsletter_today_title']; ?>" size="100" />
	                <label for="ean_newsletter_weekly_title"><?php _e("Weekly Stories Title"); ?></label>
	                <input type="text" name="ean_newsletter_weekly_title" value="<?php echo $_POST['ean_newsletter_weekly_title']; ?>" size="100" />
	                <label for="ean_newsletter_monthly_title"><?php _e("Monthly Stories Title"); ?></label>
	                <input type="text" name="ean_newsletter_monthly_title" value="<?php echo $_POST['ean_newsletter_monthly_title']; ?>" size="100" />
	                <label for="ean_newsletter_yearly_title"><?php _e("Yearly Stories Title"); ?></label>
	                <input type="text" name="ean_newsletter_yearly_title" value="<?php echo $_POST['ean_newsletter_yearly_title']; ?>" size="100" />
	                <label for="ean_newsletter_detailed_title"><?php _e("Detailed Stories Title"); ?></label>
	                <input type="text" name="ean_newsletter_detailed_title" value="<?php echo $_POST['ean_newsletter_detailed_title']; ?>" size="100" />
    	            <br />
	                <input type="submit" name="submitEmailTitle" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
				</div>
            </form>
        </div>
	</div>
</div>
<div>Like EAN? Give us a <a href="http://wordpress.org/extend/plugins/easy-automatic-newsletter/" target="_blank"><strong>rating</strong></a>. Need assistance or have <a href="<?php echo admin_url('admin.php').'?page=ean-admin-feedback'; ?>"><strong>suggestions</strong></a>?</div>