<?php
	if ( (isset($_POST['Submit'])) ) {
		update_option('ean_link_tweets',$_POST['ean_link_tweets']);
		update_option('ean_twitter_account',$_POST['ean_twitter_account']);
		update_option('ean_tweet_counts',$_POST['ean_tweet_counts']);
		$rs = 1;
	}
	
	$ean_link_tweets = get_option('ean_link_tweets');
	$ean_twitter_account = stripslashes(get_option('ean_twitter_account'));
	$ean_tweet_counts = stripslashes(get_option('ean_tweet_counts'));
?>
<div class="wrap">
	<?php echo "<h2>". __( 'Twitter Account', 'twitter_account' ) . "</h2>"; ?>
	<?php if($rs) { ?>
	<div id="message" class="updated">
		<p>Done.</p>
	</div>
	<?php } ?>
	
	<div class="metabox-holder">
		<div class="postbox">
			<form name="ean_twitter_form" method="post" action="<?php echo admin_url('admin.php').'?page='.$_GET['page']; ?>">
				<h3>Twitter Account</h3>
				<div class="ean_settings">
					<input type="checkbox" name="ean_link_tweets" value="1" <?php if($ean_link_tweets) echo "checked"; ?> /> <strong>Link Tweets with newsletter</strong>
					<br/><br/>
					<label for="ean_twitter_account"><?php _e("Twitter Account"); ?></label>
					<input type="text" name="ean_twitter_account" value="<?php echo $ean_twitter_account; ?>" size="20">
					
					<label for="ean_tweet_counts"><?php _e("No. of Tweets"); ?></label>
					<input type="text" name="ean_tweet_counts" value="<?php echo $ean_tweet_counts; ?>" size="20">
					<br/>
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save') ?>" />
				</div>
			</form>
		</div>
	</div>
</div>