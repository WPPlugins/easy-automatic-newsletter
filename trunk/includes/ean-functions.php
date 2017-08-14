<?php

global $ean_db_version;
$ean_db_version = "1.0";

function ean_install() {
	global $wpdb;
	global $ean_db_version;

	$table_name = $wpdb->prefix . "ean_newsletter";


	$sql = "CREATE TABLE " . $table_name . " (
			id int(11) NOT NULL AUTO_INCREMENT,
			email varchar(100) NOT NULL,
			token varchar(50) NOT NULL,
			status varchar(1) NOT NULL DEFAULT 'A' COMMENT 'D-Disabled, A-Active',
			subscription varchar(1) NOT NULL DEFAULT 'V' COMMENT 'V-Unverified, S-Subscribed, U-Unsubscribed',
			added_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			ip varchar(50) NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY email (email)
		);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);

	$subscription_mail_subject = "Confirmation Required";
	$subscription_mail_msg = '<p>Thanks for your subscription. Please click on the below link to confirm your subscription.</p>';
	$subscription_mail_msg .= '{confirm_url}';
	$confirmation_msg = 'Your subscription has been confirmed. We hope that you enjoy our
					newsletter. The next newsletter is scheduled for {next_send_date}';

	$unsubscribed_msg = 'Successfully unsubscribed.';
	$unsubscribed_mail_subject = 'You have unsubscribed';
	$unsubscribed_mail_msg = 'We are sorry to see you go.';

	if (!get_option('ean_from_address')){
		add_option('ean_from_address', get_bloginfo('admin_email'));
	}

	if (!get_option('ean_subject')){
		add_option('ean_subject', "Newsletter From ".get_bloginfo('name'));
	}

	if (!get_option('ean_email_frequency')){	
		add_option('ean_email_frequency', "w");
	}

	if (!get_option('ean_ean_last_post_id')){
		add_option('ean_ean_last_post_id', "1");
	}

	if (!get_option('ean_subscription_mail_subject')){
		add_option('ean_subscription_mail_subject', $subscription_mail_subject);
	}

	if (!get_option('ean_subscription_mail_msg')){	
		add_option('ean_subscription_mail_msg', $subscription_mail_msg);
	}

	if (!get_option('ean_confirmation_msg')){
		add_option('ean_confirmation_msg', $confirmation_msg);
	}

	if (!get_option('ean_unsubscribed_msg')){
		add_option('ean_unsubscribed_msg', $unsubscribed_msg);
	}

	if (!get_option('ean_unsubscribed_mail_subject')){
		add_option('ean_unsubscribed_mail_subject', $unsubscribed_mail_subject);
	}

	if (!get_option('ean_unsubscribed_mail_msg')){	
		add_option('ean_unsubscribed_mail_msg', $unsubscribed_mail_msg);
	}	

	if (!get_option('ean_tweet_counts')){	
		add_option('ean_tweet_counts',10);
	}

	if (!get_option('ean_facebook_appid')){
		add_option('ean_facebook_appid');
	}

	if (!get_option('ean_facebook_secret')){
		add_option('ean_facebook_secret');
	}
	
	if (!get_option('ean_facebook_feedid')){
		add_option('ean_facebook_feedid');
	}	

	if (!get_option('ean_newsletter_today_title')) {
		add_option('ean_newsletter_today_title',"Todays's Stories");
	}

	if (!get_option('ean_newsletter_weekly_title')) {
		add_option('ean_newsletter_weekly_title',"This Week's Stories");
	}

	if (!get_option('ean_newsletter_monthly_title')) {
		add_option('ean_newsletter_monthly_title',"This Month's Stories");
	}

	if (!get_option('ean_newsletter_yearly_title')) {
		add_option('ean_newsletter_yearly_title',"This Year's Stories");
	}

	if (!get_option('ean_newsletter_detailed_title')) {
		add_option('ean_newsletter_detailed_title',"Detailed Stories");
	}

	if (!get_option('ean_cp_cron')) {
		add_option('ean_cp_cron',"yes");
	}

	add_option("ean_db_version", $ean_db_version);

}

function makeRequest($url){
	if(function_exists('curl_init')) {
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
		if((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		}

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		return @curl_exec($ch);
	}
	else {
		return @file_get_contents($url);
	}
}

function ean_facebook_feeds($appid, $secret, $feed_id) {

	$facebook = new Facebook(array(
	  'appId'  => $appid,
	  'secret' => $secret,
	  'cookie' => true,
	));
	$output = '';
	$info = $facebook->api('/'. $feed_id .'/?date_format=U');

	if($info) {
		$output .= "<p style='font-weight: bold; font-size: 18px'><a href='http://www.facebook.com/profile.php?id=". $info['id'] ."' title='". $info['name'] ."'>". $info['name'] ."</a></p>\n";
	}
	$content = $facebook->api('/'. $feed_id .'/feed?date_format=U');

	foreach($content['data'] as $item) {
				

					$from_pic = "<img src='https://graph.facebook.com/".$item['from']['id']."/picture' alt='".$item['from']['name']."' width='32' height='32' />";
					$from = "<p style='text-decoration: none; font-weight: bold'>";
						$from .= "<a href='http://www.facebook.com/". $item['from']['id'] ."'>". $item['from']['name'] ."</a>";
					$from .= "</p>\n";



				$message = isset($item['message']) ? trim($item['message']) : null;
				$message = preg_replace('/\n/', '<br />', $message);
				$likes = '';
				if (isset($item['likes'])) {
					foreach ($item['likes']['data'] as $like) {

							$likes .= '<img src="https://graph.facebook.com/' . $like['id'].'/picture" alt="'.$like['name'].'" />' . '\n';

					}
				}

				$descriptioin = isset($item['description']) ? trim($item['description']) : null;

				$descriptioin = preg_replace(array('/((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}\/\S*)/', '/\n/'), array("<a href='$1'>\\1</a>", '<br />'), $descriptioin);
				
				$story = isset($item['story']) ? trim($item['story']) : null;
				

				if ( isset($item['properties']) ) {
					$properties = null;
					foreach( $item['properties'] as $key => $property ) {
						$date = is_date($property['text']);
						if ( $date != false ) {
							$date = date('d M Y g:ia', $date);
							$properties .= ( $date != false ) ? $date : $property['text'];
						} else
							$properties .= $property['text'];

							if ( $key != (count($item['properties']) - 1) )
								$properties .= "<br />";
												
					}// End foreach( $item['properties'] as $key => $property )
				
				// End if ( isset($item['properties']) )
				} else
					$properties = null;

				// Format the date
				$published = date('d M Y g:ia', $item['created_time']);

				// Check for comments
				if ( $item['comments']['count'] > 0 ) {
					$comments = ( $item['comments']->count > 1 ) ? __(' Comments') : __(' Comment');
					$comments = ' &bull; '. $item['comments']->count . $comments;
				} else
					$comments = __(' &bull; No Comments');

				// Create a link to the item on facebook
				$item_link = preg_split('/_/', $item['id']);
				$item_link = 'http://www.facebook.com/'. $item_link[0] .'/posts/'. $item_link[1];

				$date = "<p class='fb-date'>";
					$date .= "<a href='". $item_link ."' target='_blank' class='quiet' title='". __('See this post on Facebook') ."'>". $published . $comments ."</a>";
				$date .= "</p>\n";
				
				$freq = get_option('ean_email_frequency');
				switch($freq) {
					case 'd':
						$feed_start_date = strtotime( '-1 day', strtotime(date('d M Y')));
						break;
					case 'w':
						$feed_start_date = strtotime( '-1 week', strtotime(date('d M Y')));
					break;
					case 'm':
						$feed_start_date = strtotime( '-1 month', strtotime(date('d M Y')));
					break;
					case 'b':
						$feed_start_date = strtotime( '-15 day', strtotime(date('d M Y')));
					break;
					case 'q':
						$feed_start_date = strtotime( '-3 month', strtotime(date('d M Y')));
					break;
					case 'y':
						$feed_start_date = strtotime( '-1 year', strtotime(date('d M Y')));
					break;
					default:
						$feed_start_date = strtotime( '-1 day', strtotime(date('d M Y')));
				}

				
				if (strtotime($published) >= $feed_start_date) {
					
				$output .= "<div style='border-top: 1px dotted #ccc; margin: 10px 0; padding-top: 5px; overflow: auto; width: 670px' id='fb-feed-". $item['id'] ."'>\n";	
				$output .= "<div style='float: left; width: 50px;'>".$from_pic."</div><div style='float: left; width: 620px'>";
					
					// See if we should display who posted it
					if ( $limit == false )
						$output .= $from;
					
					// The actual users status
					if ( $message != null  )
						$output .= "<p class='message'>". $message ."</p>\n";
					else if ( $story != null )
						$output .= "<p class='story'>". $story ."</p>\n";
					
					// See if there's something like a link or video to show.
					if ( isset($item['link']) || $descript != null || $properties != null ) {
						
						$output .= "<blockquote style='overflow: auto;'>\n";
						
							$output .= "<p>\n";
							
								if ( isset($item['picture']) ) {
									$img = "<img src='". htmlentities($item['picture']) ."' style='float: left; margin: 5px 10px 5px' />\n";
									if ( isset($item['link']) )
										$output .= "<a href='". esc_attr($item['link']) ."' class='the_link'>$img</a>\n";
								}
								
								// The item link
								if ( isset($item['link']) && isset($item['name']) )
									$output .= "<a href='". esc_attr($item['link']) ."' style='font-family: Georgia,Bitstream Charter,serif; text-decoration: none; font-style: italic; font-size: 14px'>". $item['name'] ."</a>\n";
								
							$output .= "</p>\n";
								
							// The item caption
							if ( isset($item['caption']) && preg_match('/((?:http[s]?:\/\/)|www\.)([^\s]+)/', $item['caption']) )
								$output .= "<p class='caption'><a href='". esc_attr($item['caption']) ."'>". $item['caption'] ."</a><p>\n";
							else if ( isset($item['caption']) )
								$output .= "<p class='caption'>". $item['caption'] ."</p>\n";
							
							
							if ( $descriptioin != null || $properties != null ) {
								
								$output .= "<p>\n";
														
								if ( $descriptioin != null )
									$output .= "<span style='font-family: Georgia,Bitstream Charter,serif'>". $descriptioin ."</span>\n";
						
								if ( $descriptioin != null && $properties != null )
									$output .= "<br /><br />";

								if ( $properties != null )
									$output .= $properties;
								
								$output .= "</p>\n";
								
							}

						$output .= "</blockquote>\n";
						
					}

					$output .= $date;
				
				$output .= "</div></div>\n";
				
				// Add one to our count tally
				$count++;
			} // Check the feed start date based on email frequency
				// If we reached our limit
				if( $count == $maxitems)
					break;

			}// End foreach
		if ($count == 0) {
			$output .= "<div style='font-weight: bold; font-size: 15px; margin-top: 20px'>No Feeds Found</div>";
		}
	return $output;
}

function displaySubscriptionBox($show_powered_link=0){
	$output ='<script type="text/javascript">
				$jean = jQuery.noConflict();
				$jean(document).ready(function($) {
					$("#frmSubscribe").submit(function(event){
						event.preventDefault();
						if ( $("#email").val() ){
							var reg = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-]{1,})+\.)+([a-zA-Z0-9]{2,})+$/;
							if( !reg.test($("#email").val()) ){
								$("#subscribeInfo").html("<span class=\"error\">Invalid email id.</span>");
							}else{
								$("#subscribeInfo").html("<strong>Loading.......</strong>");
								var data = "email="+$("#email").val();
								var url = "'.WP_PLUGIN_URL.'/easy-automatic-newsletter/includes/ean-subscription.php";
								$.post(url, data, function(response) {
									$("#subscribeInfo").html(response);
									$("#email").val("");
								});
							}
						}else{
							$("#subscribeInfo").html("<span class=\"error\">Please enter email id.</span>");
						}
					});
				});
			</script>';

	$output .= '<div id="ean"><form action="" id="frmSubscribe" method="post">'
				.'<span id="subscribeInfo"></span>'
				.'<input type="text" name="email" id="email" value="Email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>'
				.'<input type="submit" name="Subscribe" value="Subscribe"/>'
				.'<center><span class="powered-by">Powered by <br/>';

	if($show_powered_link){			
		$output .= '<a href="http://www.pillarsupport.com/the-easy-automatic-newsletter-for-wordpress" target="_blank">';
	}

	$output .= 'Easy Automatic Newsletter Lite v2.7.2';
	if($show_powered_link){ 
		$output .= '</a>';
	}

	$output .= '</span></center>';	
	$output .= '</form></div>';
	return $output;
}

function mail_head(){
	$head = '<div style="width:700px;border:1px solid #EEEDED;background:#F6F5F5;padding:10px 20px;font-family:Arial,Verdana;">';
	if(get_option('ean_logo')){
		$head .= '<img src="'.stripslashes(get_option('ean_logo')).'" alt="'.get_option('name').'" />';
	}	

	$head .= '<div style="border:1px solid #cccccc;border-radius:6px;padding:10px 15px;margin:10px 0px;background:#fff;">';
	return $head;
}

function mail_foot($unsub_link=null){
	$foot = '</div>
		<span style="color:#666;font-size:11px;">'.
			stripslashes(get_option('ean_privacy_policy'));
	if( $unsub_link ){
		$foot .= '<br/>If you no longer wish to receive email from us, simply click on <a href="'.$unsub_link.'" target="_blank">unsubscribe</a> link.';
	}	

	$foot .= '<p>Powered by <a href="http://wordpress.org/extend/plugins/easy-automatic-newsletter" target="_blank">Easy Automatic Newsletter Lite v2.7.2</a></p></span></div>';
	return $foot;

}

function replace_keywords($text,$data){

	$settings['ean_email_frequency'] = get_option('ean_email_frequency');
	$settings['ean_last_send_date'] = get_option('ean_last_send_date');

	$setting_details = array();

	if( $settings['ean_email_frequency'] == 'm' ){
		$setting_details['next_date'] = '+1 month';
	}elseif( $settings['ean_email_frequency'] == 'w' ){
		$setting_details['next_date'] = '+1 week';
	}elseif( $settings['ean_email_frequency'] == 'y' ){
		$setting_details['next_date'] = '+1 year';
	}elseif( $settings['ean_email_frequency'] == 'd' ){
		$setting_details['next_date'] = '+1 day';
	}

	if($settings['ean_last_send_date']){
		$last_date = $settings['ean_last_send_date'];
		$next_send_date = strtotime($setting_details['next_date'],strtotime($last_date));
		$next_send_date = date('d M Y', $next_send_date);
	}

	// Check the cron is running from CPanel or Wordpress wp_event_schedule
	// 'yes' ==> 'CPanel Cron'
	// 'no' ==> 'WP Cron

	if (get_option('ean_cp_cron') == 'no') {
		$next_send_date = date('d M Y', wp_next_scheduled('ean_cron_action'));
	}

	$text = str_replace('{confirm_url}', '<a href="'.$data['act_link'].'">'.$data['act_link'].'</a>', $text);
	$text = str_replace('{unsub_link}', '<a href="'.$data['unsub_link'].'">'.$data['unsub_link'].'</a>', $text);
	$text = str_replace('{user_email}', $data['user_email'], $text);
	$text = str_replace('{site_name}', get_bloginfo('name'), $text);
	$text = str_replace('{site_url}', get_bloginfo('siteurl'), $text);
	$text = str_replace('{admin_email}', get_bloginfo('admin_email'), $text);
	$text = str_replace('{next_send_date}', $next_send_date, $text);

	return $text;
}


function process_tweets($tweet){
	$tweet = preg_replace("#(^|[\n ])@([^ \"\t\n\r<]*):#ise", "'\\1<a href=\"http://www.twitter.com/\\2\" rel=\"external nofollow\" target=\"_blank\">@\\2</a> :'", $tweet);  
	$tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" rel=\"external nofollow\" target=\"_blank\">\\2</a>'", $tweet);
	$tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" rel=\"external nofollow\" target=\"_blank\">\\2</a>'", $tweet);
	$tweet = preg_replace("(#([a-zA-Z0-9\_]+))", "<a href=\"http://twitter.com/search?q=%23\\1\" rel=\"external nofollow\" target=\"_blank\">\\0</a>", $tweet);

	return $tweet;
}

function sendNewsletter( $admin=null ){

	global $wpdb;

	$settings['ean_email_frequency'] = get_option('ean_email_frequency');
	$settings['ean_last_post_id'] = get_option('ean_last_post_id');
	$settings['ean_from_address'] = stripslashes(get_option('ean_from_address'));
	$settings['ean_from_name'] = stripslashes(get_option('ean_from_name'));
	$settings['ean_subject'] = stripslashes(get_option('ean_subject'));
	$settings['ean_test_email'] = stripslashes(get_option('ean_test_email'));
	$settings['ean_bchar_limit'] = stripslashes(get_option('ean_bchar_limit'));
	$settings['ean_categories'] = get_option('ean_categories');
	$settings['ean_facebook_appid'] = get_option('ean_facebook_appid');
	$settings['ean_facebook_secret'] = get_option('ean_facebook_secret');
	$settings['ean_facebook_feedid'] = ean_get_facebook_id(get_option('ean_facebook_feedid'));

	$args = array(
			'numberposts'     => -1,
			'offset'          => 0,
			'orderby'         => 'ID',
			'order'           => 'ASC',
			'post_type'       => 'post',
			'post_status'     => 'publish',
			'category'     	  => $settings['ean_categories']
			);

	if( $settings['ean_email_frequency'] == 'b' ){	
		add_filter( 'posts_where', 'filter_where' );
		$query = new WP_Query(array('category__in' => explode(',', $settings['ean_categories']), 'post_status' => 'publish'));

		foreach($query->posts as $val){
			$recent_posts[] = $val;
		}

		$stories_freq = "Top Stories";
		remove_filter( 'posts_where', 'filter_where' );
	}else{

		if( $settings['ean_email_frequency'] == 'm' ){
			$args['monthnum'] = date('m');
			$args['year'] = date('Y');
			$stories_freq = stripslashes(get_option('ean_newsletter_monthly_title'));
		}elseif( $settings['ean_email_frequency'] == 'w' ){
			$args['w'] = date('W');
			$args['monthnum'] = date('m');
			$args['year'] = date('Y');
			$stories_freq = stripslashes(get_option('ean_newsletter_weekly_title'));
		}elseif( $settings['ean_email_frequency'] == 'y' ){
			$args['year'] = date('Y');
			$stories_freq = stripslashes(get_option('ean_newsletter_yearly_title'));
		}elseif( $settings['ean_email_frequency'] == 'd' ){
			$args['day'] = date('d');
			$args['monthnum'] = date('m');
			$args['year'] = date('Y');
			$stories_freq = stripslashes(get_option('ean_newsletter_today_title'));
		}

		$recent_posts = get_posts($args);
	}

	$post_details = NULL;
	foreach ( $recent_posts as $key=>$val ) {

		if( $val->ID > $settings['ean_last_post_id'] ){
			$post_details[$key]['post_id'] = $val->ID;
			$post_details[$key]['post_title'] = $val->post_title;
			$post_details[$key]['post_permalink'] = get_permalink($val->ID);
			$post_details[$key]['post_content'] = $val->post_content;
			$categories = get_the_category($val->ID);
			$cat_string = '';
			foreach( $categories as $category ){
				$cat_string .= ", ".$category->name;
			}
			$post_details[$key]['categories'] = trim($cat_string,",");
			$post_details[$key]['post_date'] = date('d M Y',strtotime($val->post_date));

			$ean_last_post_id = $val->ID;
		}

	}

	wp_reset_query();
	$feeds = '';
	if ($settings['ean_facebook_feedid'] != 0) {
		$feeds = ean_facebook_feeds($settings['ean_facebook_appid'], $settings['ean_facebook_secret'], $settings['ean_facebook_feedid']);
	}

	if ( get_option('ean_link_tweets') ){
		$tweets_url = "http://api.twitter.com/1/statuses/user_timeline.json?include_rts=true&screen_name=".get_option('ean_twitter_account')."&count=".get_option('ean_tweet_counts')."&exclude_replies=1&trim_user=1";
		$data = makeRequest($tweets_url);
	}


	$tweets = '';

	if( $data ){
		foreach ( json_decode($data) as $data){
			$tweets .= '<p style="padding:0px 0px 7px 0px;border-bottom:1px solid #F4F4F4;">'.process_tweets($data->text).'</p>';
		}
	}

	if( $post_details ) {
		$newsletter_mail = mail_head().'<h2>'.$stories_freq.'</h2>';
		foreach ( $post_details as $key=>$post ) {	
			$newsletter_mail .= '<a href="#'.$post['post_id'].'">'.$post['post_title'].'</a><br/>';
		}

		$newsletter_mail .= '<br/><h2>'.stripslashes(get_option('ean_newsletter_detailed_title')).'</h2>';

		foreach ( $post_details as $key=>$post ) {	
			$newsletter_mail .= '<a name="'.$post['post_id'].'"></a><a href="'.$post['post_permalink'].'"><h3 style="margin-bottom:3px;">'.$post['post_title'].'</h3></a>';
			$newsletter_mail .= '<span style="font-size:10px;">'.$post['post_date'].' &nbsp; &nbsp; '.$post['categories'].'</span>';
			if( $settings['ean_bchar_limit'] ){
				$post_content = strip_shortcodes($post['post_content']);
				$post_content = str_replace(']]>', ']]&gt;', $post_content);

				//$post_content = strip_tags($post_content);

				$newsletter_mail .= '<div style="padding-bottom:10px;border-bottom:1px solid #F0EFEF">'.substr(nl2br($post_content),0,$settings['ean_bchar_limit']).' <b>(<a href="'.$post['post_permalink'].'">...</a>)</b></div>';
			}else{
				$newsletter_mail .= '<div style="padding-bottom:10px;border-bottom:1px solid #F0EFEF">'.nl2br($post['post_content']).'</div>';
			}	
		}	

		$twitter_account = stripslashes(get_option('ean_twitter_account'));

		if($twitter_account){
			$newsletter_mail .= '<br/><h2>Tweets by <a href="http://twitter.com/'.$twitter_account.'">@'.$twitter_account.'</h2>'.$tweets;
		}	

		// Check the permission to add the feeds with newsletter
		if ( get_option('ean_add_fbfeed') ) {
			if ($feeds != '') {
				$newsletter_mail .= $feeds;
			}
		}

		if( $admin==1 ){
			$newsletter_mail .= mail_foot("#");
			$sent_status = wp_mail(get_bloginfo('admin_email'), $settings['ean_subject'], $newsletter_mail);
		}elseif( $admin==2 ){
			$newsletter_mail .= mail_foot("#");
			$sent_status = wp_mail($settings['ean_test_email'], $settings['ean_subject'], $newsletter_mail);
		}else{
			$table_name = $wpdb->prefix . "ean_newsletter";
			$subscriber_q = " SELECT * FROM ".$table_name." WHERE 1=1 ";
			$subscriber_q .= " AND status='A' AND subscription='S' ";
			$subscribers = $wpdb->get_results($subscriber_q, ARRAY_A);
			if( $subscribers ){
				$counter = 100;
				$i = 0;
				foreach ( $subscribers as $subscriber ){
					if ($i % $counter == 0){
						sleep(10);
					}

					$unsub_link = WP_PLUGIN_URL."/easy-automatic-newsletter/ean-unsubscribed.php?eid=".$subscriber['id'].'&act_code='.$subscriber['token'];
					$newsletter_mail_user = $newsletter_mail.mail_foot($unsub_link);
					$sent_status = wp_mail($subscriber['email'], $settings['ean_subject'], $newsletter_mail_user);
				}		

				update_option('ean_last_send_date',date("Y-m-d h:i:s"));
				update_option('ean_last_post_id',$ean_last_post_id);

				$newsletter_mail .= mail_foot("#");
				$sent_status = wp_mail(get_bloginfo('admin_email'), $settings['ean_subject'], $newsletter_mail);
			}
		}

	}

	return $sent_status;
}

function ean_pagination($total_rec, $current_page=0, $per_page, $page_name, $extra_url){

	$pagination = "";
	if($current_page<0 || $current_page==0){
		$p = 1;
	}else{
		$p = $current_page;
	}

	$total_pages = intval($total_rec/$per_page) ;

	if( ($total_rec%$per_page) > 0 ){
		$total_pages++ ;
	}

	$pagination .= '<span class="pagination-links">';

	if($total_pages >= 1){
		if($p>1){
			$pagination .= '<a href="'.$page_name.$extra_url.'&current_page=1" class="first-page">&laquo;</a>&nbsp;';
			$pagination .= '<a href="'.$page_name.$extra_url.'&current_page='. ($p-1) .'" class="prev-page">&lsaquo;</a>&nbsp;';
		}else{
			$pagination .= '<a href="#" class="first-page disabled">&laquo;</a>&nbsp;';
			$pagination .= '<a href="#" class="prev-page disabled">&lsaquo;</a>&nbsp;';
		}

		$pagination .= "<span class='paging-input'><input class='current-page' title='Current page' type='text' name='current_page' value='".$current_page."' size='1' /> of <span class='total-pages'>".$total_pages."</span></span>";

		if( $p>=1 && $p<$total_pages ){
			$pagination .= '<a href="'.$page_name.$extra_url.'&current_page='. ($p+1) .'" class="next-page">&rsaquo;</a>&nbsp;';
			$pagination .= '<a href="'.$page_name.$extra_url.'&current_page='.$total_pages.'" class="last-page">&raquo;</a>&nbsp;';
		}else{
			$pagination .= '<a href="#" class="next-page disabled">&rsaquo;</a>&nbsp;';
			$pagination .= '<a href="#" class="last-page disabled">&raquo;</a>&nbsp;';
		}
	}

	$pagination .= "</span>";

	return ($pagination) ;

}

function filter_where( $where = '' ) {
	$where .= " AND post_date > '" . date('Y-m-d', strtotime('-15 days')) . "' ";
	return $where;
}

add_action('ean_cron_action','sendNewsletter');
add_filter('cron_schedules','ean_cron_definer');

function ean_cron_definer($schedules){

	if (get_option('ean_email_frequency') == 'w') {
		$starttime = 7 * 24 * 60 * 60;
		$schedules['weekly'] = array( 'interval' => $starttime, 'display' => __('Weekly Once') );
	}

	if (get_option('ean_email_frequency') == 'm') {
		$starttime = 30 * 24 * 60 * 60;
		$schedules['monthly'] = array( 'interval' => $starttime, 'display' => __('Monthly (30 Day) Once') );
	}

	if (get_option('ean_email_frequency') == 'b') {
		$starttime = 15 * 24 * 60 * 60;
		$schedules['2x/monthly'] = array( 'interval' => $starttime, 'display' => __('Monthly Twice (15 Day) Once') );
	}

	if (get_option('ean_email_frequency') == 'q') {
		$starttime = 90 * 24 * 60 * 60;
		$schedules['quarterly'] = array( 'interval' => $starttime, 'display' => __('Quarterly (90 Day) Once') );
	}

	if (get_option('ean_email_frequency') == 'y') {
		$starttime = 365 * 24 * 60 * 60;
		$schedules['yearly'] = array( 'interval' => $starttime, 'display' => __('Yearly (365 Day) Once') );
	}

	return $schedules;
}


function ean_cron() {
	$timestamp = time();
	$ean_cron_day = get_option('ean_cron_day');
	$ean_cron_hours = get_option('ean_cron_hrs');
	$ean_cron_mins = get_option('ean_cron_mins');

	switch($ean_cron_day) {
		case 1: $dayName = 'Sunday'; break;		
		case 2: $dayName = 'Monday'; break;
		case 3: $dayName = 'Tuesday'; break;
		case 4: $dayName = 'Wednesday'; break;
		case 5: $dayName = 'Thusday'; break;
		case 6: $dayName = 'Friday'; break;
		case 7: $dayName = 'Saturday'; break;
		default: $dayName = 'Sunday';
	}

// Detect the month to setup quarterly schedule

	$month = date('m');
	if ($month <= 3) {
		$startQuarter = 'April '.date('Y').' ' . $ean_cron_hours .' hours '. $ean_cron_mins . ' minutes';
	} else if (($month > 3) && ($month <= 6)) { 
		$startQuarter = 'July '.date('Y').' ' . $ean_cron_hours .' hours '. $ean_cron_mins . ' minutes';
	} else if (($month > 6) && ($month <= 9)){ 
		$startQuarter = 'October '.date('Y').' ' . $ean_cron_hours .' hours '. $ean_cron_mins . ' minutes';
	} else {
		$startQuarter ='January '.(date('Y')+1).' ' . $ean_cron_hours .' hours '. $ean_cron_mins . ' minutes';
	}

	$eanDailyStartTime = strtotime('Tomorrow ' . $ean_cron_hours . ' hours ' . $ean_cron_mins . ' minutes');
	$eanWeekStartTime = strtotime($dayName . ' ' . $ean_cron_hours . ' hours ' . $ean_cron_mins . ' minutes');
	$eanMonthlyTwiceStartTime = strtotime('+15 day '.$ean_cron_hours.' hours '.$ean_cron_mins.' minutes');
	$eanMonthlyStartTime = mktime($ean_cron_hours,$ean_cron_mins,0,(date('m')+1),1);
	$eanQuarterlyStartTime = strtotime($startQuarter);
	$eanYearlyStartTime = strtotime('January '.(date('Y')+1).' '.$ean_cron_hours.' hours '.$ean_cron_mins.' minutes');

	switch (get_option('ean_email_frequency')) {
		case 'd':
			$freq = 'daily';
			$timestamp = $eanDailyStartTime;
			break;

		case 'w':
			$freq = 'weekly';
			$timestamp = $eanWeekStartTime;
			break;

		case 'm':
			$freq = 'monthly';
			$timestamp = $eanMonthlyStartTime;
			break;

		case 'b':
			$freq = '2x/monthly';
			$timestamp = $eanMonthlyTwiceStartTime;
			break;

		case 'q':
			$freq = 'quarterly';
			$timestamp = $eanQuarterlyStartTime;
			break;

		case 'y':
			$freq = 'yearly';
			$timestamp = $eanYearlyStartTime;
			break;

			default:
			$freq = 'daily';
			$timestamp = $eanDailyStartTime;
	}

	wp_clear_scheduled_hook('ean_cron_action');
	wp_schedule_event($timestamp, $freq, 'ean_cron_action');
}



function ean_clear_cron() {
	wp_clear_scheduled_hook('ean_cron_action');
}


function ean_get_facebook_id($gUrl) {

	if (strpos($gUrl, "set=")) {
		$url_type = 'image';
	} elseif (strpos($gUrl, "id=")) {
		$url_type = 'album';
	} else {
		$url_type = 'not_exist';
	}

	switch($url_type) {
		case 'album':
			$parsed_url = parse_url($gUrl);
			parse_str($parsed_url['query'], $result);
			$profile_id = $result['id'];
			break;
		case 'image':
			$parsed_url = parse_url($gUrl);
			parse_str($parsed_url['query'], $result);
			$profile_id = substr($result['set'], strrpos($result['set'], '.')+1);		
		break;
		case 'not_exist':
			$profile_id = '0';
		break;
	}
	
	return $profile_id;
}

?>