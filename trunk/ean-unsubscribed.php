<?php
require_once('../../../wp-config.php');
require_once('../../../wp-includes/wp-db.php');
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
		if( $subscribers->subscription != 'U' ){
			$rs = $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "ean_newsletter set subscription='U' where id=%d and token=%s", $id, $token));
	

			if($rs){
				$data['act_link'] = WP_PLUGIN_URL.'/easy-automatic-newsletter/ean-confirmation.php?eid='.$id.'&act_code='.$token;
				$data['unsub_link'] = WP_PLUGIN_URL.'/easy-automatic-newsletter/ean-unsubscribed.php?eid='.$id.'&act_code='.$token;
				$data['user_email'] = $subscribers->email;
				echo replace_keywords(stripslashes(get_option('ean_unsubscribed_msg')),$data);
				

				$user_mail['subject'] = replace_keywords(stripslashes(get_option('ean_unsubscribed_mail_subject')),$data);
				$user_mail['body'] = mail_head().replace_keywords(stripslashes(get_option('ean_unsubscribed_mail_msg')),$data).mail_foot();
				wp_mail($subscribers->email, $user_mail['subject'], $user_mail['body']);
			}else{
				echo '<span style="color:#C00;">Error in processing. Please try again later.</span>';
			}
		}else{
			echo '<span style="color:#C00;">You already unsubscribed.</span>';
		}
	}else{
		echo '<span style="color:#C00;">Sorry you are not subscribed.</span>';
	}
}
echo '</div></div>';
get_sidebar();
get_footer();
?>