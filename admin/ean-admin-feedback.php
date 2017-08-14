<?php
require_once("jira/utils.php");

	if ( (isset($_POST['Submit'])) ) {

		$error = $email_error = 0;
		if( empty($_POST['feedback_subject']) || empty($_POST['feedback_msg']) ){
			$error = 1;
		}else{
			if( !empty($_POST['feedback_email']) ){
				if (!is_email($_POST['feedback_email'])) {
					$email_error = 1;
				}
			}

			$new_issue = array(
					'fields' => array(
					'project' => array('key' => 'EAN'),
					'summary' => 'Feedback',
					'description' => $_POST['feedback_msg'],						
					'issuetype' => array('name' => $_POST['feedback_subject'])						
					)
				);				

			$jira_url = "https://pillar.atlassian.net";
			$result = create_issue($new_issue);			

			if( !$email_error ){
				$msg = mail_head();
				if( !empty($_POST['feedback_email']) ){
					$msg .= "From : ".$_POST['feedback_email']."<br/><br/>";
				}
				$msg .= nl2br($_POST['feedback_msg']).mail_foot();
 					
				$rs = wp_mail('chief@pillarcc.com', "EAN Feedback :: ".$_POST['feedback_subject'], $msg);
				if( $rs ){
					$_POST = NULL;
				}	
			}	
		}	
	}
	function create_issue($issue) {
		return post_to('issue', $issue);
	}
	$projects = get_projects();

	if($_POST['feedback_subject'] == "Comment"){
		$comment = "selected";
	} else if ($_POST['feedback_subject'] == "Bug") {
		$bug = "selected";
	} else if ($_POST['feedback_subject'] == "Feature Request") {
		$request = "selected";
	} 
		

?>


<div class="wrap">
	<?php echo "<h2>". __( 'Feedback', 'feedback' ) . "</h2>"; ?>
	<p><strong>Email us your comments / suggestions / questions</strong></p>
	<?php if($rs) { ?>
	<div id="message" class="updated">
		<p>Sent.</p>
	</div>
	<?php }elseif( $error ){ ?>
	<div class="error fade">
		<p>Please enter subject &amp; message.</p>
	</div>	
	<?php }elseif( $email_error ){ ?>
	<div class="error fade">
		<p>Please enter valid email id.</p>
	</div>	
	<?php } ?>
	<div class="metabox-holder">
		<div class="postbox">
			<form name="ean_status_form" method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">
				<h3>Mail Us</h3>
				<div class="ean_settings">

					
					<label for="feedback_email"><?php _e("Email (Optional)"); ?></label>
					<input type="text" name="feedback_email" value="<?php echo $_POST['feedback_email']; ?>" size="70">
					
					

					<label for="feedback_subject"><?php _e("Subject"); ?></label>
					<!-- <input type="text" name="feedback_subject" value="" size="150"> -->
					<select name="feedback_subject" style="width:100%;">						
							<option value="Bug" <?php echo $bug;?>>Bug</option>
							<option value="Comment" selected>Comment</option>
							<option value="Feature Request" <?php echo $request;?>>Feature Request</option>
					</select>
					

					<label for="feedback_msg"><?php _e("Message"); ?></label>
					<textarea name="feedback_msg" cols="152" rows="20"><?php echo $_POST['feedback_msg']; ?></textarea>
					<br/>
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Send') ?>" />
				</div>
			</form>
		</div>
	</div>
</div>