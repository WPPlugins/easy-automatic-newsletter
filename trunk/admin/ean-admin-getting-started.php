<div class="wrap">
	<?php echo "<h2>". __( 'Getting Started', 'getting_started' ) . "</h2>"; ?>
	<p><h3>NOTE: For Linux-based Hosted Sites Only</strong></h3>
	<p>Thank you for activating The Easy Automatic Newsletter Plugin for 
	Wordpress (EAN). <strong>For plugin updates/news follow us on Twitter <a href="https://twitter.com/#!/chiefatlarge" target="_blank">@chiefatlarge</a></strong></p>

	<p>This intro is designed to help you configure the plugin and to get 
	you up and running quickly. After configuring everything you can get 
	back to blogging and Tweeting and the Easy Automatic Newsletter 
	plugin will handle your monthly newsletter. The world loves great 
	content in any form. Cheers!</p>
	
	<h3>Newsletter Post Settings</h3>
	<ol class="ean_list">
		<li>Click on Settings (under the Easy Automatic Newsletter tab in your Dashboard menu)</li>
		<li>Configure the frequency and timing of your emails in the Settings screen first and before you adjust anything in your Cron Job utility</li>
		<li>Set the Email Frequency from the dropdown (if you do not see the frequency you need, we do not currently offer it)</li>
		<li>Set the time in hours (24 hour time), Minutes, and on which day of the week to send your newsletter</li>
		<li>Choose the categories to send from. Choose the number of characters to include for each blog post within your newsletter (must be numeric)</li>
		<li>Click the Save Changes button when done AND BEFORE PROCEEDING.</li>
		<li>The grey box below the Save Changes button will contain the Cron job configuration for your defined settings which will assist you in configuring the Cron in your control panel (you must do this in order for the Easy Automatic Newsletter plugin to work properly)</li>
	</ol>
	
	<h3>Newsletter Tweets Settings</h3>
	
	<p>If you wish to feed in your Tweets:</p>
	<ol>
		<li type="a">Select "Link Tweets with newsletter"</li>
		<li type="a">Enter your Twitter account name</li>
		<li type="a">Enter the number of Tweets to include (leave empty for the last 20)</li>
	</ol>	
	<p>NOTE: Unlike blog posts, the amount of Tweets included in your 
	newsletter is irrespective of the date you Tweeted them. In other 
	words, it is possible to send the same Tweets in a newsletter as 
	were sent with a prior newsletter. To avoid that, 
	just keep Tweeting!</p>

	<h3>Import Subscribers</h3>
	<ol>
		<li>Click Import Subscribers to add a comma separated list of 
		email addresses.</li>
		<li>If you've added addresses click the check box to affirm 
		that the recipients have authorized their subscription.</li>
	</ol>
	
	<h3>Subscription Messages</h3>
	
	<p>Subscription Messages is where you can customize messages 
	produced when someone subscribes and unsubscribes from your 
	list.</p>

	<p>At the top of this page are short codes you can use in 
	the message body to insert information.</p>

	<p>The default settings can be used as is or you can 
	customize them to be in your 'voice'. Be sure to keep the 
	gist of the message or else it will be confusing to 
	subscribers.</p>
	<p><strong>Opt-in Email Configuration</strong></p>
	
	<p>This is the email received to the email address entered 
	using the widget.</p>

	<p>You can modify the subject and body but be sure to keep 
	the gist of the message intact or else you can confuse your 
	subscriber. Remember that it should be painless to 
	subscribe to your newsletter.</p>

	<p>Click Save when done.</p>

	<p><strong>Confirmation Message</strong></p>

	<p>After a subscriber receives the opt-in email and clicks 
	on the confirmation link they are taken to a page on your 
	website with the message you customize here.</p>

	<p>Click Save if you edit the default message.</p>

	<p>NOTE: Behind the scenes, once a subscriber clicks the 
	link their status in the Subscribers section turns to 
	Verified</p>

	<p><strong>Unsubscribed Message</strong></p>

	<p>When someone unsubscribes from your newsletter using the 
	link at the bottom of the email newsletter they will be 
	sent to a page on your site where the message you customize 
	will appear.</p>

	<p>As always, if you make an edit click the Save button.</p>

	<p>NOTE: Behind the scenes, the subscriber's status will 
	change. You are expected to abide by anti-spamming laws.</p>
	
	<p><strong>Unsubscribed Email</strong></p>

	<p>In addition to an onscreen confirmation of being 
	unsubscribed, the user will receive one more email 
	indicating that they've been unsubscribed. You can 
	customize this message to be whatever you'd like. 
	Something short and polite will always work.</p>

	<p>Be sure to click the Save button if you make an edits.</p>

	<h3>Placing the Subscribe Widget</h3>
	<p>From the list of Available Widgets, locate the widget 
	called Easy Automatic Newsletter. Drag that to one of your 
	sidebars to place the widget.</p>
	
	<h3>Newsletter Status</h3>
	<ol>
		<li>You can use this section to send test emails to the admin or a defined email address.</li>
	</ol>	
	NOTE: If the message "No post to send" appears at the top it also means that you cannot send a test email either.
	<br/><br/>
	<strong><p>Keep blogging and tweeting great content!</p>
	<p>We'd like to hear from you. We've included a 
	<a href="<?php echo admin_url('admin.php').'?page=ean-admin-feedback'; ?>">Feedback form</a> inside the plugin and encourage you to share 
	your thoughts, comments and success stories</a></strong>
</div>