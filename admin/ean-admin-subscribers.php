<?php
	$status = isset($_GET["status"]) ? $_GET["status"] : ( isset($_POST["status"]) ? $_POST["status"] :'');
	$s_status = isset($_GET["s_status"]) ? $_GET["s_status"] : ( isset($_POST["s_status"]) ? $_POST["s_status"] :'');
	$current_page = isset($_GET["current_page"]) ? $_GET["current_page"] : ( isset($_POST["current_page"]) ? $_POST["current_page"] : '');
	$pp = 10;

	global $wpdb;

/**
*** Import Subscribers form action
**/
	if ( (isset($_POST['imSubmit']) )) {
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
							$irs = $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "ean_newsletter set ".$st_query." where email='".$email."'" ));
						}else{
							$subscriber_data['email'] = $email;
							$subscriber_data['status'] = 'A';
							$subscriber_data['token'] = md5(rand());
							$subscriber_data['subscription'] = 'S';
							$subscriber_data['added_date'] = date("Y-m-d h:i:s");
							$subscriber_data['ip'] = $_SERVER['REMOTE_ADDR'];
							$irs = $wpdb->insert($wpdb->prefix . 'ean_newsletter', $subscriber_data);
						}
						if( $irs ) { 
							$success = 1;
							$_POST = NULL;
						}
					}
				}
			}else{
				$ierror = "Please check the affirmation below.";
			}
		}else{
			$ierror = "Please enter the list of subscribers.";
		}	

	}

/**/


	if ( $_POST['ean_sub_hidden'] == 'Y' ) {
		$error = $rs = 0;
		if( $_POST['id'] ){
			$u_query = '';
			if( $_POST['action']== 'm_active' ) {
				$u_query = " status = 'A' ";
			}elseif( $_POST['action']== 'm_disable' ) {
				$u_query = " status = 'D' ";
			}elseif( $_POST['action']== 'm_verify' ) {
				$u_query = " subscription = 'S' ";
			}elseif( $_POST['action']== 'm_delete' ) {
				$delete_u = "DELETE FROM ".$wpdb->prefix. "ean_newsletter WHERE id IN (".implode(",",$_POST['id']).")";
				$wpdb->query($delete_u);
			}
			if( $u_query ){ 
				$rs = $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "ean_newsletter set ".$u_query." where id IN (".implode(",",$_POST['id']).")"));
			}
		}else{
			if( !empty($_POST['action']) )
				$error = 1;
		}
	}	

	if ( empty($current_page) ) {
		$current_page = 1;
		$next = 0;
	}else {
		$next = ($current_page-1) * $pp;
	}	
	$extra_url = "&status=$status&s_status=$s_status";
	$cond_query = '';
	if( $status )
		$cond_query .= " AND status='".$status."' ";
	if( $s_status )
		$cond_query .= " AND subscription='".$s_status."' ";		
	$table_name = $wpdb->prefix . "ean_newsletter";
	$subscriber_q = "SELECT * FROM ".$table_name." WHERE 1=1 ";
	$subscriber_q .= $cond_query;
	$subscribers_total = $wpdb->get_results($subscriber_q, ARRAY_A);	
	$subscriber_q .= " LIMIT ". $next .", ". $pp;
	$subscribers = $wpdb->get_results($subscriber_q, ARRAY_A);	
	$subscriber_all = "SELECT COUNT(*) AS count FROM  ".$table_name;
	$subscribers_all = $wpdb->get_results($subscriber_all, ARRAY_A);
	$subscriber_a = "SELECT COUNT(*) AS count FROM  ".$table_name. " WHERE status='A' ";
	$subscribers_a = $wpdb->get_results($subscriber_a, ARRAY_A);
	$subscriber_d = "SELECT COUNT(*) AS count FROM  ".$table_name. " WHERE status='D' ";
	$subscribers_d = $wpdb->get_results($subscriber_d, ARRAY_A);
	$subscriber_v = "SELECT COUNT(*) AS count FROM  ".$table_name. " WHERE subscription='V' ";
	$subscribers_v = $wpdb->get_results($subscriber_v, ARRAY_A);
	$subscriber_s = "SELECT COUNT(*) AS count FROM  ".$table_name. " WHERE subscription='S' ";
	$subscribers_s = $wpdb->get_results($subscriber_s, ARRAY_A);
	$subscriber_u = "SELECT COUNT(*) AS count FROM  ".$table_name. " WHERE subscription='U' ";
	$subscribers_u = $wpdb->get_results($subscriber_u, ARRAY_A);
	$subscriber_as = "SELECT COUNT(*) AS count FROM  ".$table_name. " WHERE status='A' AND subscription='S' ";
	$subscribers_as = $wpdb->get_results($subscriber_as, ARRAY_A);
	$pagination = ean_pagination(count($subscribers_total), $current_page, $pp, admin_url('admin.php').'?page='.$_GET['page'], $extra_url);

?>


<div class="wrap">
	<?php echo "<h2>" . __( 'Subscribers', 'ean_subscribers' ) . "</h2>"; ?>
	<?php if($success) { ?>
	<div id="message" class="updated">
		<p>Imported Successfully.</p>
	</div>
	<?php }if( $ierror ){ ?>
		<div class="error fade">
			<p><?php echo $ierror; ?></p>
		</div>
	<?php } if ($invalid_email){ ?>
		<div class="error fade">
			<p><?php echo implode(",",$invalid_email)." these email id's are not valid hence excluded."; ?></p>
		</div>
	<?php } ?>
	<?php if($rs) { ?>
	<div id="message" class="updated">
		<p>Done.</p>
	</div>
	<?php } 
		if( $error ){
	?>
	<div class="error fade">
		<p>Please select subscribers.</p>
	</div>
	<?php } ?>
	<ul class='subsubsub'>
		<li><a href='admin.php?page=ean-admin-subscribers' <?php if ( empty($status) && empty($s_status) ) echo 'class="current"'; ?> >All <span class="count">(<?php echo $subscribers_all[0]['count']; ?>)</span></a> |</li>

		<li><a href='admin.php?page=ean-admin-subscribers&status=A' <?php if ( $status=='A' && empty($s_status) ) echo 'class="current"'; ?>>Active <span class="count">(<?php echo $subscribers_a[0]['count']; ?>)</span></a> |</li>

		<li><a href='admin.php?page=ean-admin-subscribers&status=D' <?php if ( $status=='D' && empty($s_status) ) echo 'class="current"'; ?>>Disabled <span class="count">(<?php echo $subscribers_d[0]['count']; ?>)</span></a> |</li>

		<li><a href='admin.php?page=ean-admin-subscribers&s_status=V' <?php if ( $s_status=='V' && empty($status) ) echo 'class="current"'; ?>>Unverified <span class="count">(<?php echo $subscribers_v[0]['count']; ?>)</span></a> |</li>

		<li><a href='admin.php?page=ean-admin-subscribers&s_status=S' <?php if ( $s_status=='S' && empty($status) ) echo 'class="current"'; ?>>Subscribed <span class="count">(<?php echo $subscribers_s[0]['count']; ?>)</span></a> |</li>

		<li><a href='admin.php?page=ean-admin-subscribers&s_status=U' <?php if ( $s_status=='U' && empty($status) ) echo 'class="current"'; ?>>Unsubscribed <span class="count">(<?php echo $subscribers_u[0]['count']; ?>)</span></a> |</li>

		<li><a href='admin.php?page=ean-admin-subscribers&status=A&s_status=S' <?php if ( $status=='A' && $s_status=='S' ) echo 'class="current"'; ?>>Active &amp; Subscribed <span class="count">(<?php echo $subscribers_as[0]['count']; ?>)</span></a></li>

	</ul>

	<div class="div-clear"></div>

	<form name="ean_sub_form" method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">
	<div class="tablenav top">
		<div class="alignleft actions">
			<input type="hidden" name="ean_sub_hidden" value="Y">
			<input type="hidden" name="status" value="<?php echo $status; ?>">
			<input type="hidden" name="s_status" value="<?php echo $s_status; ?>">
			<select name='action'>
				<option value="" selected='selected'>Bulk Actions</option>
				<option value='m_active'>Activate</option>
				<option value='m_disable'>Deactivate</option>
				<option value='m_verify'>Verify</option>
				<option value='m_delete'>Delete</option>
			</select>
			<input type="submit" name="" id="doaction" class="button-secondary action" value="Apply" />
		</div>
		<div class='tablenav-pages'><?php echo $pagination; ?></div>
	</div>
	<div class="metabox-holder">
		<div class="postbox"> 
			<table class="widefat">
				<thead>
					<tr>
						<th>Id</th>
						<th>Email</th>
						<th>Status</th>
						<th>Subscription Status</th>
						<th>Subscribed Date</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Id</th>
						<th>Email</th>
						<th>Status</th>
						<th>Subscription Status</th>
						<th>Subscribed Date</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
						if(!empty($subscribers)){
						foreach ( $subscribers as $subscriber ) {
					?>
					<tr>
						<td><input type="checkbox" name="id[]" value="<?php echo $subscriber['id']; ?>" /></td>
						<td><?php echo stripslashes($subscriber['email']); ?></td>
						<td>
							<?php 
								if($subscriber['status'] == 'A')
									echo '<span class="ean_active">Active</span>';
								elseif($subscriber['status'] == 'D')
									echo '<span class="ean_disabled">Disabled</span>';
							?>
						</td>
						<td>
							<?php 
								if($subscriber['subscription'] == 'V')
									echo '<span class="ean_unverified">Unverified</span>';
								elseif($subscriber['subscription'] == 'S')
									echo '<span class="ean_subscribed">Subscribed</span>';
								elseif($subscriber['subscription'] == 'U')
									echo '<span class="ean_unsubscribed">Unsubscribed</span>';
							?>
						</td>
						<td><?php echo date('d M Y',strtotime($subscriber['added_date'])); ?></td>
				   </tr>
				   <?php } 
				   }else{
						echo '<tr><td colspan="5">No records found.</td></tr>';
				   }
				   ?>
				</tbody>
			</table>
		</div> 
	</div>
	</form>
	<form name="ean_import_form" method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">	
		<div class="metabox-holder">
			<div class="postbox"> 
				<h3>Import Subscribers</h3>
				<div class="ean_settings">
					<p>Paste the comma separated list of emails in the box below :</p>
					<textarea name="ean_import_subscribers" cols="180" rows="16"><?php echo $_POST['ean_import_subscribers']; ?></textarea>
					<input type="checkbox" name="affirm" value="1" <?php if($_POST['affirm'] == 1) echo "checked"; ?>> 
					I affirm that the emails I am importing have authorized me to send them emails.
					<br/><br/>
					<input type="submit" name="imSubmit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</div>	
			</div>
		</div>
	</form>
</div>