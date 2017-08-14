<?php
	global $wpdb;
	if ( (isset($_POST['Submit']) )) {
		if( !empty($_POST['ean_import_subscribers']) ){
			if( isset($_POST['affirm']) ){
				$success = 0;
				$emails = explode(",",$_POST['ean_import_subscribers']);
				foreach ( $emails as $email) {
					$email = trim($email);
					if (!is_email($email)) {
						$invalid_email[] = $email;
					}else{
						$query = "select id from " . $wpdb->prefix . "ean_newsletter where email='".$email."'";
						$subscriber = $wpdb->get_results($query);
						if( $subscriber )  {
							$st_query = " status = 'A', subscription = 'S' ";
							$rs = $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "ean_newsletter set ".$st_query." where email='".$email."'" ));
						}else{
							$subscriber_data['email'] = $email;
							$subscriber_data['status'] = 'A';
							$subscriber_data['token'] = md5(rand());
							$subscriber_data['subscription'] = 'S';
							$subscriber_data['added_date'] = date("Y-m-d h:i:s");
							$subscriber_data['ip'] = $_SERVER['REMOTE_ADDR'];
							$rs = $wpdb->insert($wpdb->prefix . 'ean_newsletter', $subscriber_data);
						}
						if( $rs ) { 
							$success = 1;
							$_POST = NULL;
						}
					}
				}
			}else{
				$error = "Please check the affirmation below.";
			}
		}else{
			$error = "Please enter the list of subscribers.";
		}	
	}
?>
<div class="wrap">
	<?php echo "<h2>" . __( 'Import Subscribers', 'ean_import' ) . "</h2>"; ?>
	<?php if($success) { ?>
	<div id="message" class="updated">
		<p>Imported Successfully.</p>
	</div>
	<?php }if( $error ){ ?>
		<div class="error fade">
			<p><?php echo $error; ?></p>
		</div>
	<?php } if ($invalid_email){ ?>
		<div class="error fade">
			<p><?php echo implode(",",$invalid_email)." these email id's are not valid hence excluded."; ?></p>
		</div>
	<?php } ?>
	<form name="ean_import_form" method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">	
		<div class="metabox-holder">
			<div class="postbox"> 
				<h3>Import</h3>
				<div class="ean_settings">
					<p>Paste the comma separated list of emails in the box below :</p>
					<textarea name="ean_import_subscribers" cols="180" rows="32"><?php echo $_POST['ean_import_subscribers']; ?></textarea>
					<input type="checkbox" name="affirm" value="1" <?php if($_POST['affirm'] == 1) echo "checked"; ?>> 
					I affirm that the emails I am importing have authorized me to send them emails.
					<br/><br/>
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</div>	
			</div>
		</div>
	</form>
</div>