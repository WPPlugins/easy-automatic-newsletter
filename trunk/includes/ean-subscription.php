<?php
require_once('../../../../wp-config.php');
require_once('../../../../wp-includes/wp-db.php');
require_once('ean-functions.php');
global $wpdb;


$email = strtolower(trim($_POST['email']));
if (!is_email($email)) {
	echo '<span class="error">Invalid email id.</span>';
}else{
	$query = "select * from " . $wpdb->prefix . "ean_newsletter where email='".$wpdb->escape($_POST['email'])."'";
	$subscribers = $wpdb->get_results($query);
	$subscribers = $subscribers[0];
	if($subscribers){
		if( $subscribers->status == 'A' ){
			if( $subscribers->subscription == 'V' ){
				echo '<span class="error">Already subscribed. Please verify your account.</span>';
			}elseif( $subscribers->subscription == 'U' ){
				$token = md5(rand());
				$rs = $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "ean_newsletter set subscription='V',token='".$token."' where id=%d ", $subscribers->id));
				if($rs){
					$data['act_link'] = WP_PLUGIN_URL.'/easy-automatic-newsletter/ean-confirmation.php?eid='.$subscribers->id.'&act_code='.$token;
					$data['unsub_link'] = WP_PLUGIN_URL.'/easy-automatic-newsletter/ean-unsubscribed.php?eid='.$subscribers->id.'&act_code='.$token;
					$data['user_email'] = $email;
					$user_mail['subject'] = replace_keywords(stripslashes(get_option('ean_subscription_mail_subject')),$data);
					$user_mail['body'] = mail_head().replace_keywords(stripslashes(get_option('ean_subscription_mail_msg')),$data).mail_foot($data['unsub_link']);
					

					$admin_mail['subject'] = "This user has re-subscribed from ".get_bloginfo('name');
					$admin_mail['body'] = mail_head()."Email : ".$email.mail_foot();
					

					wp_mail($email, $user_mail['subject'], $user_mail['body']);
					wp_mail(get_bloginfo('admin_email'), $admin_mail['subject'], $admin_mail['body']);
					

					echo '<span class="success">Thank you. Please check your email for the opt-in email. Click on link to confirm your subscription.</span>';
				}	
			}elseif( $subscribers->subscription == 'S' ){
				echo '<span class="error">Already subscribed.</span>';
			}
		}else{
			echo '<span class="error">Sorry! Your account is disabled by admin.</span>';
		}
	}else{
		$subscriber_data['email'] = $wpdb->escape($email);
		$subscriber_data['status'] = 'A';
		$subscriber_data['token'] = md5(rand());
		$subscriber_data['subscription'] = 'V';
		$subscriber_data['added_date'] = date("Y-m-d h:i:s");
		$subscriber_data['ip'] = $_SERVER['REMOTE_ADDR'];

		$wpdb->insert($wpdb->prefix . 'ean_newsletter', $subscriber_data);

		$subscriber_data['id'] = $wpdb->insert_id;
		$data['act_link'] = WP_PLUGIN_URL.'/easy-automatic-newsletter/ean-confirmation.php?eid='.$subscriber_data['id'].'&act_code='.$subscriber_data['token'];
		$data['unsub_link'] = WP_PLUGIN_URL.'/easy-automatic-newsletter/ean-unsubscribed.php?eid='.$subscriber_data['id'].'&act_code='.$subscriber_data['token'];
		$data['user_email'] = $email;
		$user_mail['subject'] = replace_keywords(stripslashes(get_option('ean_subscription_mail_subject')),$data);
		

		$unsub_link = WP_PLUGIN_URL."/easy-automatic-newsletter/ean-unsubscribed.php?eid=".$subscriber_data['id'].'&act_code='.$subscriber_data['token'];
		$user_mail['body'] = mail_head().replace_keywords(stripslashes(get_option('ean_subscription_mail_msg')),$data).mail_foot($unsub_link);
		

		$admin_mail['subject'] = "New user subscription from ".get_bloginfo('name');
		$admin_mail['body'] = mail_head()."Email : ".$email.mail_foot();

		
		wp_mail($email, $user_mail['subject'], $user_mail['body']);
		wp_mail(get_bloginfo('admin_email'), $admin_mail['subject'], $admin_mail['body']);
		

		echo '<span class="success">Thank you. Please check your email for the opt-in email. Click on link to confirm your subscription.</span>';
	}
}
?>