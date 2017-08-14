<?php
	global $wpdb;

	if ( (isset($_POST['Submit']) )) {

		update_option('ean_logo', $_POST['ean_logo']);
		update_option('ean_from_name', $_POST['ean_from_name']);
		update_option('ean_from_address', $_POST['ean_from_address']);
		update_option('ean_subject', $_POST['ean_subject']);
		update_option('ean_privacy_policy', $_POST['ean_privacy_policy']);
		
		$rs = 1;
		
	} elseif ( (isset($_POST['submitSubMsgs']) )) {
		
		update_option('ean_subscription_mail_subject', $_POST['ean_subscription_mail_subject']);
		update_option('ean_subscription_mail_msg', $_POST['ean_subscription_mail_msg']);
		update_option('ean_confirmation_msg', $_POST['ean_confirmation_msg']);
		update_option('ean_unsubscribed_msg', $_POST['ean_unsubscribed_msg']);
		update_option('ean_unsubscribed_mail_subject', $_POST['ean_unsubscribed_mail_subject']);
		update_option('ean_unsubscribed_mail_msg', $_POST['ean_unsubscribed_mail_msg']);
		update_option('ean_newsletter_today_title', $_POST['ean_newsletter_today_title']);
		update_option('ean_newsletter_weekly_title', $_POST['ean_newsletter_weekly_title']);
		update_option('ean_newsletter_monthly_title', $_POST['ean_newsletter_monthly_title']);
		update_option('ean_newsletter_yearly_title', $_POST['ean_newsletter_yearly_title']);
		update_option('ean_newsletter_detailed_title', $_POST['ean_newsletter_detailed_title']);
		
		$rs = 1;
		
	} elseif ( (isset($_POST['submitSocial']) )) {
	
		update_option('ean_link_tweets',$_POST['ean_link_tweets']);
		update_option('ean_twitter_account',$_POST['ean_twitter_account']);
		update_option('ean_tweet_counts',$_POST['ean_tweet_counts']);		

		update_option('ean_add_fbfeeds', $_POST['ean_add_fbfeeds']);
		update_option('ean_facebook_appid',$_POST['ean_facebook_appid']);
		update_option('ean_facebook_secret',$_POST['ean_facebook_secret']);
		update_option('ean_facebook_feedid',$_POST['ean_facebook_feedid']);	

		$rs = 1;

	}elseif ( (isset($_POST['SubmitCron']) )) {

		$ean_cp_cron = $_POST['ean_cp_cron'];

		update_option('ean_cp_cron', $ean_cp_cron);
		if (isset($_POST['ean_command_type'])) {
			update_option('ean_command_type', $_POST['ean_command_type']);
		}
		update_option('ean_email_frequency', $_POST['ean_email_frequency']);
		update_option('ean_bchar_limit', $_POST['ean_bchar_limit']);
		update_option('ean_test_email', $_POST['ean_test_email']);
		update_option('ean_cron_hrs', $_POST['ean_cron_hrs']);
		update_option('ean_cron_mins', $_POST['ean_cron_mins']);
		update_option('ean_cron_day', $_POST['ean_cron_day']);
		update_option('ean_last_send_date','');

		if( $_POST['ean_categories'] ){
			update_option('ean_categories', implode(",",$_POST['ean_categories']));
		} else {
			update_option('ean_categories', '');
		}

		$rs = 1;

		// Check if the cpanel cron option is disabled on admin settings page

		if ($ean_cp_cron == 'yes') {
			wp_clear_scheduled_hook('ean_cron_action');
		} else {			
			wp_clear_scheduled_hook('ean_cron_action');
			ean_cron();
		}
	}

/**
*** Other Settings Section field values from DB
**/
	$ean_logo = stripslashes(get_option('ean_logo'));
	$ean_from_name = stripslashes(get_option('ean_from_name'));
	$ean_from_address = stripslashes(get_option('ean_from_address'));
	$ean_subject = stripslashes(get_option('ean_subject'));
	$ean_privacy_policy = stripslashes(get_option('ean_privacy_policy'));
	
/**
** Get the Social Media field values from DB
**/
	$ean_link_tweets = get_option('ean_link_tweets');
	$ean_twitter_account = stripslashes(get_option('ean_twitter_account'));
	$ean_tweet_counts = stripslashes(get_option('ean_tweet_counts'));

	$ean_add_fbfeeds = get_option('ean_add_fbfeeds');
	$ean_facebook_appid = get_option('ean_facebook_appid');
	$ean_facebook_secret = get_option('ean_facebook_secret');
	$ean_facebook_feedid = get_option('ean_facebook_feedid');
	
/***
**** get Cron settings section field values from DB
***/
	$ean_email_frequency = get_option('ean_email_frequency');
	$ean_categories = explode(",",get_option('ean_categories'));
	$ean_bchar_limit = get_option('ean_bchar_limit');
	$ean_test_email = get_option('ean_test_email');
	$ean_cron_hrs = get_option('ean_cron_hrs');
	$ean_cron_mins = get_option('ean_cron_mins');
	$ean_cron_day = get_option('ean_cron_day');
	$ean_cp_cron = get_option('ean_cp_cron');
	$ean_command_type = get_option('ean_command_type');
	
	$ean_cp_cron_selected = '';
	$ean_wp_cron_selected = '';	

	if ($ean_cp_cron == 'yes') {
		$ean_cp_cron_selected = 'selected';
	} else {
		$ean_wp_cron_selected = 'selected';
	}
/**
*** Get Subscription Messages field values 
**/
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

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo WP_PLUGIN_URL; ?>/easy-automatic-newsletter/jquery/jquery.tipTip.minified.js" type="text/javascript" charset="utf-8"></script>
  <link href="<?php echo WP_PLUGIN_URL; ?>/easy-automatic-newsletter/jquery/tipTip.css" type="text/css" media="screen" rel="stylesheet" />
  <link href="<?php echo WP_PLUGIN_URL; ?>/easy-automatic-newsletter/css/custom.css" type="text/css" media="screen" rel="stylesheet" />
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

  <script type="text/javascript" charset="utf-8">

	$(document).ready( function(){
		$(function(){
			$(".eanHelp").tipTip({maxWidth: "500px", edgeOffset: 10, defaultPosition: "right"});
		});
		
	    $(".cb-enable").click(function(){
	        var parent = $(this).parents('.switch');
	        $('.cb-disable',parent).removeClass('selected');
	        $(this).addClass('selected');
			$('.hidden', parent).val('yes');
			$('#cron_commands').css("display", "inherit");
			$('#cronctype').css("display", "inherit");
			$(':radio[name="ean_command_type"]').removeAttr("disabled");
	    });
		
	    $(".cb-disable").click(function(){
	        var parent = $(this).parents('.switch');
	        $('.cb-enable',parent).removeClass('selected');
	        $(this).addClass('selected');
			$('.hidden', parent).val('no');
			$('#cron_commands').css("display", "none");
			$(':radio[name^="ean_command_type"]').attr("disabled", "disabled");
			$('#cronctype').css("display", "none");
			alert('Warning! If you already have a Cronjob saved for EAN remove PRIOR to using WP Cron otherwise duplicate post may be sent.');
	    });
		
		<?php if ($ean_cp_cron == 'no') { ?>
		$(':radio["name=ean_command_type"]').attr("disabled", "disabled");
		<?php } ?>
		if ( $(':checkbox[name="ean_all_category"]').is(':checked') ) {
			$('.ean_settings').find(':checkbox').attr('checked', true);
		}
		$(':checkbox[name="ean_all_category"]').click(function () {
        	$(this).parents('.ean_settings').find(':checkbox').attr('checked', this.checked);
		});
		
		$(':checkbox[name="ean_categories[]"]').click(function () {
			if(! $(this).is(':checked') ) {
				
				$(':checkbox[name="ean_all_category"]').attr('checked', false);
			}
		});
		
		
		// Check the email frequency is daily then disable the day dropdown box
		if( $('select[name="ean_email_frequency"]').val() == 'd') {
			$('select[name="ean_cron_day"]').val("");
			$('select[name="ean_cron_day"]').attr("disabled", "disabled");
		}

		$('select[name="ean_email_frequency"]').change(function() {
			if( $('select[name="ean_email_frequency"]').val() == 'd') {
				$('select[name="ean_cron_day"]').val("");
				$('select[name="ean_cron_day"]').attr("disabled", "disabled");
			} else {
				$('select[name="ean_cron_day"]').removeAttr("disabled");
			}
		});

	});

  </script>

<div class="wrap">

	<?php echo "<h2>" . __( 'Easy Automatic Newsletter Lite v2.8.0', 'ean_settings' ) . "</h2>"; ?>

    <?php
	   If (is_plugin_active('easy-automatic-newsletter/core-control.php')) {
    	  //plugin is activated
	   } else {
		   include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		   activate_plugin('easy-automatic-newsletter/core-control.php');
	   }
	?>

	<?php if($rs) { ?>

	<div id="message" class="updated">

		<p>Done.</p>

	</div>

	<?php } ?>

	<form name="ean_settings_form" method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">		
		<div class="metabox-holder">
			<div class="postbox">
				<h3>Settings</h3>
				<div class="ean_settings">

                    <p><strong>Scheduling Type:</strong></p>
                    <p style="line-height:20px">We provide two methods to schedule your newsletter delivery: Cronjob or WP Cron.</p>                    
                    <div class="row">
                    	<div class="col1">
                    		<label for="choose_cron"><?php _e("Choose the Cron type you wish:"); ?>&nbsp;<img src="<?php echo WP_PLUGIN_URL; ?>/easy-automatic-newsletter/images/help.png" title="<p style=line-height:20px>The Cronjob method (preferred; Linux only) requires that you also configure your Control Panel's cron utility. Though this is a separate screen from WordPress, it will execute the command (generated below) at the time and day defined. Some web hosts have difficulty executing the command. We provide 3 different commands to help with that. If you still have trouble, we recommend you ask your host to troubleshoot this for you.</p><p style=line-height:20px>The WP Cron method method allows you to configure the scheduler from within WordPress, HOWEVER it is not automatically executed. It must be triggered by someone landing on your homepage on or around the time you wish to deliver a newsletter. This is ideal for very busy websites or for a not-so-busy site with an admin with time to trigger the newsletter.</p>" class="eanHelp" /></label>
                    	</div>
                    	<div class="col2">
                    		<p class="field switch">
						    <label class="cb-enable <?php echo $ean_cp_cron_selected; ?>"><span>Cronjob</span></label>
						    <label class="cb-disable <?php echo $ean_wp_cron_selected; ?>"><span>WP Cron</span></label>
                            <input type="hidden" name="ean_cp_cron" value="<?php echo $ean_cp_cron; ?>" class="hidden" />
						</p>
                    	</div>
					</div>
                    <br /><br /><br /><br />
                    <div class="row">
                    	<div class="col1">
                    		<label for="email_frequency"><?php _e("Email Frequency"); ?></label>
                    	</div>
                    	<div class="col2">
                    		<select name="ean_email_frequency">
								<option value="d" <?php if($ean_email_frequency=='d') echo "selected"; ?>>Daily</option>
								<option value="w" <?php if($ean_email_frequency=='w') echo "selected"; ?>>Weekly</option>
								<option value="b" <?php if($ean_email_frequency=='b') echo "selected"; ?>>2x/month</option>
								<option value="m" <?php if($ean_email_frequency=='m') echo "selected"; ?>>Monthly</option>
								<option value="q" <?php if($ean_email_frequency=='q') echo "selected"; ?>>Quarterly</option>                        
								<option value="y" <?php if($ean_email_frequency=='y') echo "selected"; ?>>Yearly</option>
							</select>
						</p>
                    	</div>
					</div>
					<div class="row">
						<div class="col1">
							<label>Server Date/Time:&nbsp;&nbsp;</span></label><br />
						</div>
						<div class="col2">
							<?php echo date('d M Y H:i', time()); ?>
						</div>
					</div>

					<div class="row">
						<div class="col1">
							<label for="ean_cron_time"><?php _e("Send Time: (24 hour time):"); ?></label>
						</div>
						<div class="col2">
							Hrs[0-23] <select name="ean_cron_hrs" style="display:inline">
								<?php for ($i=0; $i<24; $i++) { ?>
									<option value="<?php echo $i; ?>"<?php echo ($ean_cron_hrs == $i) ? ' selected = "selected"' : ''; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
							&nbsp; Mins[0-59] <select name="ean_cron_mins" style="display:inline">
								<?php for ($i=0; $i<60; $i++) { ?>
									<option value="<?php echo $i; ?>"<?php echo ($ean_cron_mins == $i) ? ' selected = "selected"' : ''; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
							&nbsp; on
							<select name="ean_cron_day" style="display:inline;" >
								<option value="">Select</option>
								<option value="1" <?php if($ean_cron_day=='1') echo "selected"; ?>>Sunday</option>
								<option value="2" <?php if($ean_cron_day=='2') echo "selected"; ?>>Monday</option>
								<option value="3" <?php if($ean_cron_day=='3') echo "selected"; ?>>Tuesday</option>
								<option value="4" <?php if($ean_cron_day=='4') echo "selected"; ?>>Wednesday</option>
								<option value="5" <?php if($ean_cron_day=='5') echo "selected"; ?>>Thursday</option>
								<option value="6" <?php if($ean_cron_day=='6') echo "selected"; ?>>Friday</option>
								<option value="7" <?php if($ean_cron_day=='7') echo "selected"; ?>>Saturday</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col1">
							<label for="category">Select Blog Categories to Send from</label>
						</div>
						<div class="col2">
							<?php
							$args = array( 'hide_empty' => 0 );
							$categories = get_categories($args);
							?>
							<div style="float:left;margin-right:10px">
							<input type="checkbox" name="ean_all_category" value="all"<?php if ($ean_categories[0] == '') echo " checked"; ?> /> Select All &nbsp;<br /><br />
						</div>
							<?php
							foreach ( $categories as $category ){
								$checked = '';
								if( $ean_categories ){
									if ( in_array($category->cat_ID, $ean_categories) ){
										$checked = "checked";
									}
								}
								echo '<input type="checkbox" name="ean_categories[]" value="'.$category->cat_ID.'" '.$checked.' />'.$category->name.' &nbsp; ';
							}
							?>
						</div>
					</div>
					<div class="row">
						<div class="col1">
							<label for="char_limit">Blog Post Character Limit:<span id="post_help" style="margin-left: 5px;"><img src="<?php echo WP_PLUGIN_URL; ?>/easy-automatic-newsletter/images/help.png" title="For Large Blog Posts Only. Leave Blank to show whole post; define numeric value based on ~5 characters per word" class="eanHelp" /></span></label>
						</div>
						<div class="col2">
							<input type="text" name="ean_bchar_limit" value="<?php echo $ean_bchar_limit; ?>" size="36">
						</div>
					</div>
					<div class="row">
						<div class="col1">
							<label for="test_email">Email to Send Tests</label>
						</div>
						<div class="col2">
							<input type="text" name="ean_test_email" value="<?php echo $ean_test_email; ?>" size="36">
						</div>
					</div>
					<div class="row">
						<div id="cronctype">
							<div class="col1">

									<label for="ean_command_type"><?php _e('Select Command Type:'); ?></label>
							</div>
							<style type="text/css">
								.radio-command{
									float: left;
									width: 70px;
								}
								.custom{
									margin-top: 20px;
								}
							</style>
							<div class="col2">
								<div class="radio-command">
									<input type="radio" name="ean_command_type" style="float:left" value="php" <?php if($ean_command_type=='php') echo "checked"; ?> /> <span style="display:inline">php</span>
								</div>
								<div class="radio-command">
									<input type="radio" name="ean_command_type" value="wget" style="float:left" <?php if($ean_command_type=='wget') echo "checked"; ?> /> <span style="display:inline">wget</span>
								</div>
								<div class="radio-command">
									<input type="radio" name="ean_command_type" value="curl" style="float:left" <?php if($ean_command_type=='curl') echo "checked"; ?> /> <span style="display:inline">curl</span>
								</div>
							</div>
						</div>
					</div>

					<br /><br />





					<input type="submit" name="SubmitCron" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				<div id="cron_commands" <?php echo ($ean_cp_cron=='yes') ? 'style="display: inherit"' : 'style="display: none"'; ?> class="custom">

					<strong>AFTER Clicking Save Changes, apply the settings below to your cron:</strong><br /><br />
					NOTE: Please USE ONLY ONE of the 3 commands listed. We provide 3 because some servers prefer the other commands.
					<p class="command-bg">
						Minute : 
						<?php
							if( empty($ean_cron_mins) )
								echo "0";
							else
								echo $ean_cron_mins;
						?><br />
						Hour : 
						<?php 
							if( empty($ean_cron_hrs) )
								echo "0";
							else
								echo $ean_cron_hrs;
						?><br />
						Day : 
						<?php 
							if ($ean_email_frequency == 'd' || $ean_email_frequency == 'w')
								echo "*";
							elseif($ean_email_frequency == 'b')
								echo "1,15";
							else
								echo "1";
						?>
						<br />
						Month : 
						<?php 
							if ($ean_email_frequency == 'y')
								echo "1";
							elseif($ean_email_frequency == 'q')
								echo "1,4,7,10";
							else
								echo "*";
						?>
						<br />
						Weekday : 
						<?php 
							if ($ean_email_frequency == 'w' && empty($ean_cron_day))
								echo "0";
							elseif ($ean_email_frequency == 'd' || empty($ean_cron_day))
								echo "*";
							else
								echo $ean_cron_day-1;
						?><br />
                        <?php if (get_option('ean_command_type') == 'php') { ?>
						Command : 
						<?php echo "php -q ".WP_PLUGIN_DIR."/easy-automatic-newsletter/ean-schedule-newsletter.php > /dev/null 2>&1"; ?><br />
                        <?php }	elseif (get_option('ean_command_type') == 'wget') { ?>
                        Command : <?php echo "wget -q " .get_bloginfo('url')."/wp-content/plugins/easy-automatic-newsletter/ean-schedule-newsletter.php > /dev/null 2>&1"; ?><br />
                        <?php } elseif (get_option('ean_command_type') == 'curl') { ?>
                        Command : <?php echo "curl -s ".get_bloginfo('url')."/wp-content/plugins/easy-automatic-newsletter/ean-schedule-newsletter.php"; ?>
                        <?php } //else { ?>
						<?php //echo "php -q ".WP_PLUGIN_DIR."/easy-automatic-newsletter/ean-schedule-newsletter.php > /dev/null 2>&1"; ?>
                        <?php //} ?>
					</p>
                  </div> <!-- #cron_command -->
				</div>

			</div>

			<div class="postbox"> 
				<h3>Other Settings</h3>
				<div class="ean_settings">
					<label for="logo"><?php _e("Logo Path"); ?><span style="font-weight: normal"> ( Provide the URL to the image that will appear at the top of your newsletter. )</span></label>
					<input type="text" name="ean_logo" value="<?php echo $ean_logo; ?>" size="100">
					<label for="from_name"><?php _e("Sender's Name"); ?></label>
					<input type="text" name="ean_from_name" value="<?php echo $ean_from_name; ?>" size="100">
					<label for="from_address"><?php _e("Sender's Email"); ?></label>
					<input type="text" name="ean_from_address" value="<?php echo $ean_from_address; ?>" size="100">
					<label for="subject"><?php _e("Subject Line"); ?></label>
					<input type="text" name="ean_subject" value="<?php echo $ean_subject; ?>" size="100">
					<label for="privacy_policy"><?php _e("Privacy Policy"); ?><span style="font-weight: normal"> ( This appears
  in the footer of your email newsletter )</span></label>
					<textarea name="ean_privacy_policy" cols="102" rows="5"><?php echo $ean_privacy_policy; ?></textarea>
                    <br />

					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />

				</div>	
			</div>
			<div class="postbox"> 
				<h3>Social Media Integration</h3>
				<div class="ean_settings">
<!-- Twitter Account Settings-->

					<input type="checkbox" name="ean_link_tweets" value="1" <?php if($ean_link_tweets) echo "checked"; ?> /> <strong>Link Tweets with newsletter</strong>

					<br /><br /><div style="position:relative">

					<label for="ean_twitter_account"><?php _e("Twitter Account"); ?></label>
					<input type="text" name="ean_twitter_account" value="<?php echo $ean_twitter_account; ?>" size="20">
					<label for="ean_tweet_counts"><?php _e("No. of Tweets"); ?><span id="tweets_help" style="margin-left: 5px;"><img src="<?php echo WP_PLUGIN_URL; ?>/easy-automatic-newsletter/images/help.png" class="eanHelp" title="These are the last number of Tweets to be used. If left empty, the maximum number of Tweets used are 20" /></span></label>
					<input type="text" name="ean_tweet_counts" value="<?php echo $ean_tweet_counts; ?>" size="20">
					<br /></div>

<!-- Facebook Newsletter Settings -->
<div style="display:none;">
					<strong>Link Facebook Profile/Page Timeline with newsletter</strong><br /><br />
                    
                    <input type="checkbox" name="ean_add_fbfeeds" value="1" <?php if($ean_add_fbfeeds) echo "checked"; ?> /> <strong>Include Facebook feeds with newsletter</strong><br /><br />
					<label for="ean_facebook_appid"><?php _e("App ID"); ?><span id="app_help" style="margin-left: 5px;"><img src="<?php echo WP_PLUGIN_URL; ?>/easy-automatic-newsletter/images/help.png" class="eanHelp" title='<p>Instructions:</p><p>You will need to be logged into the Facebook account of the profile or page you wish to feed into the newsletter BEFORE proceeding.</p><ol><li>In the same browser you are logged into Facebook with, surf to: https://developers.facebook.com/apps</li><li>Click the "Create New App" button</li><li>Enter an App Name in the first field so that it validates (the word "Valid" appears in green on the right)</li><li>Enter the Security word shown and click to continue</li><li>Leave all other settings alone and click the "Save changes" button</li><li>Click the Apps tab at the top</li><li>On the left, click on your newly-named App Name</li><li>Copy and Paste the APP ID and the APP SECRET values into the appropriate fields on this page</li></ol>' /></label>
					<input type="text" name="ean_facebook_appid" value="<?php echo $ean_facebook_appid; ?>" size="100" />
                    <label for="ean_facebook_secret"><?php _e("App Secret"); ?></label>
                    <input type="text" name="ean_facebook_secret" value="<?php echo $ean_facebook_secret; ?>" size="100" />
                    <label for="ean_facebook_feedid"><?php _e("Profile URL"); ?><span id="profile_help" style="margin-left: 5px;"><img src="<?php echo WP_PLUGIN_URL; ?>/easy-automatic-newsletter/images/help.png" class="eanHelp" title='<p>Instructions:</p><ol><li>Surf to the Profile or Page you wish to use the Timeline from</li><li>Right-click on the Profile Picture (not the cover photo) and select "Copy Link Location"</li><li>Click inside the field "Profile URL" and paste it inside there</li><li>Click the "Save Changes" button</li><li>Your Feed Id will appear (it is extracted from the URL you pasted inside there)</li></ol>' /></label>
                    <input type="text" name="ean_facebook_feedid" value="<?php echo $ean_facebook_feedid; ?>" size="100" />
                    <?php if (ean_get_facebook_id(get_option('ean_facebook_feedid')) != '') { ?>
					Feed Id: <?php echo ean_get_facebook_id(get_option('ean_facebook_feedid')); ?><br /><br />
                    <?php } ?>
                    </div>
                    <br />
                    
					<input type="submit" name="submitSocial" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />

                </div>
			</div>
			<div class="postbox"> 
				<h3>Subscription Messages</h3>

					<div class="ean_settings">
	               		<p><strong>Shortcodes</strong></p>
						<ul>
							<li><strong>{confirm_url}</strong> : Confirmation link</li>
							<li><strong>{unsub_link}</strong> : Unsubscribe link</li>
							<li><strong>{user_email}</strong> : User's email</li>
							<li><strong>{site_name}</strong> : Site name </li>
							<li><strong>{site_url}</strong> : Site url</li>
							<li><strong>{admin_email}</strong> : Admin's email</li>
							<li><strong>{next_send_date}</strong> : Next newsletter scheduled date</li>
						</ul>

                	<p><strong>Opt-In E-Mail Configuration</strong></p>
                    <p>Configure the confirmation email below. Don't forget to click Save after making changes.</p>
                    <label for="ean_subscription_mail_subject"><?php _e("Subject"); ?></label>
					<input type="text" name="ean_subscription_mail_subject" value="<?php echo $_POST['ean_subscription_mail_subject']; ?>" size="150">
					<label for="ean_subscription_mail_msg"><?php _e("Message"); ?></label>
					<textarea name="ean_subscription_mail_msg" cols="152" rows="20" class="tmcEditor"><?php echo $_POST['ean_subscription_mail_msg']; ?></textarea>
                    <br />
                    <p><strong>Confirmation Message</strong></p>
                    <p>This is page and message seen after a subscriber clicks the confirmation link.</p>
                    <label for="ean_confirmation_msg"><?php _e("Message"); ?></label>
					<textarea name="ean_confirmation_msg" cols="152" rows="15" class="tmcEditor"><?php echo $_POST['ean_confirmation_msg']; ?></textarea>
                    <br />
                    <p><strong>Unsubscribed Message</strong></p>
                    <p>The text to show when user click on unsubscription link.</p>
                    <label for="ean_unsubscribed_msg"><?php _e("Message"); ?></label>
					<textarea name="ean_unsubscribed_msg" cols="152" rows="15" class="tmcEditor"><?php echo $_POST['ean_unsubscribed_msg']; ?></textarea>
                    <br />
                    <p><strong>Unsubscribed Email</strong></p>
                    <p>To send an email after unsubscribing.</p>
                    <label for="ean_unsubscribed_mail_subject"><?php _e("Subject"); ?></label>
					<input type="text" name="ean_unsubscribed_mail_subject" value="<?php echo $_POST['ean_unsubscribed_mail_subject']; ?>" size="150">
					<label for="ean_unsubscribed_mail_msg"><?php _e("Message"); ?></label>
					<textarea name="ean_unsubscribed_mail_msg" cols="152" rows="20" class="tmcEditor"><?php echo $_POST['ean_unsubscribed_mail_msg']; ?></textarea>
                    <br />
                    <p><strong>Newsletter Titles</strong></p>
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
	                <input type="submit" name="submitSubMsgs" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
                    
                </div>
			</div>
		</div>

	</form>

</div>
<div>Like EAN? Give us a <a href="http://wordpress.org/extend/plugins/easy-automatic-newsletter/" target="_blank"><strong>rating</strong></a>. Need assistance or have <a href="<?php echo admin_url('admin.php').'?page=ean-admin-feedback'; ?>"><strong>suggestions</strong></a>?</div>