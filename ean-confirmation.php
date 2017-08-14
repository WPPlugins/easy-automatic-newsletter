<?php
require_once('../../../wp-config.php');
require_once('../../../wp-includes/wp-db.php');
require_once('includes/ean-functions.php');
global $wpdb;
get_header();
echo '<div id="primary">';
echo '<div id="content">';
$id = trim($_GET['eid']);
$token = trim($_GET['act_code']);
if ( !$id && !$token ) {
	echo '<span style="color:#C00;">Sorry you are not subscribed.</span>';
}else{
	$query = "select id, email, status,subscription from " . $wpdb->prefix . "ean_newsletter where id='".$wpdb->escape($id)."' and token='".$wpdb->escape($token)."'";
	$subscribers = $wpdb->get_results($query);
	$subscribers = $subscribers[0];
	

	if ( $subscribers ){
		if( $subscribers->status == 'A' ){
			if( $subscribers->subscription == 'S' ){
				echo '<span style="color:#C00;">Already confirmed.</span>';
			}elseif( $subscribers->subscription == 'U' ){
				echo '<span style="color:#C00;">You already unsubscribed.</span>';
			}else{
				$rs = $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "ean_newsletter set subscription='S' where id=%d and token=%s", $id, $token));
				if($rs){
					$data['act_link'] = WP_PLUGIN_URL.'/easy-automatic-newsletter/ean-confirmation.php?eid='.$id.'&act_code='.$token;
					$data['unsub_link'] = WP_PLUGIN_URL.'/easy-automatic-newsletter/ean-unsubscribed.php?eid='.$id.'&act_code='.$token;
					$data['user_email'] = $subscribers->email;
					echo replace_keywords(stripslashes(get_option('ean_confirmation_msg')),$data);
				}else{
					echo '<span style="color:#C00;">Error in processing. Please try again later.</span>';
				}
			}
		}else{
			echo '<span style="color:#C00;">Sorry! Your account is disabled by admin.</span>';
		}
	}else{
		echo '<span style="color:#C00;">Sorry you are not subscribed.</span>';
	}
}
echo '</div></div>';
get_sidebar();
get_footer();
?>