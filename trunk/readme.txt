=== Easy Automatic Newsletter Lite ===
Contributors: PillarDev 	
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VH8TK2Z79VM4E
Tags: automatic, newsletter, newsletters, subscription, subscribers, newsletter signup, newsletter widget, manager newsletter
Requires at least: 3.3.1
Tested up to: 4.1.0
Stable tag: 3.2.0

Collect your blog posts for the past month and send them out at the top of a new month to subscribers. All automatically.

== Description ==

The easy-to-use WordPress plug-in that recycles your blog posts into a newsletter and sends it out automatically!

Since version 2.7 setting up a Cronjob is not required and you have the option of using WordPress's scheduler. Cronjob is not compatible with a Windows based Hosting environment but the WP Cron is.

The Easy Automatic Newsletter Lite plug-in does exactly what it is called. Instead of sweating bullets every time you need to make a newsletter, all you need to focus on with our plug-in is to keep blogging and tweeting great content.

If you can write a blog post every hour, day or week you will have enough content for your newsletter whether you send it out daily, weekly or monthly (you can also send it out  2x per month, quarterly and yearly)

If you write more than that, you'll have a robust newsletter begging the question from recipients "How do you have time to do that?" – we think that's a good thing.

Let's face it, not everyone you already know reads your blog. And in an age where recycling is good, don't let great content be used for a single audience.

Wouldn't it be nice to take a month's worth of blog posts and format it into a clean e-mail newsletter and send it out to your subscribers? That's exactly what our plug-in will do. It will also include your Tweets as well. Once configured (takes about 10 minutes), it works automatically every month. All you need to do is keep blogging and tweeting. Tweeting, by the way, is optional for the plug-in.

If you find it useful please don't forget to rate this plugin. In addition, if you really find it beneficial and have some money to spare, your donation is surely appreciated.

** Follow us on Twitter @chiefatlarge for all updates on the Easy Automatic Newsletter Lite plugin (EAN)

== Installation ==


1. You can use the built-in installer (suggested) or if you know how to handle a Zip file and use FTP,
   download the zip file and extract the contents, upload the 'easy-automatic-newsletter' folder to your plugins directory (wp-content/plugins/).
2. Activate the plugin through the 'Plugins' menu in WordPress.

Now go to **Admin Panel** and then **Easy Automatic Newsletter Lite** to configure any options as desired.

== Upgrade Notice ==

v2.8.0


== Frequently Asked Questions ==

= Is it true that the new version doesn't require you to configure a cron job in my cPanel anymore? =

Yes! We are very excited about version 2.7 and the new WP Cron feature. We witnessed a lot of users struggling with that aspect of our plugin and took action to fix it. However, you may still use the regular CronJob if preferred.

NOTE: If you wish to use WP Cron then delete your old cronjob if configured.

= What do I do about my cron job that is set up in my cPanel, when I used the old version? =

If upgrading from EAN 2.6 then you may leave your cronjob intact. If you find that your old cronjob is no longer working in 2.7 then try some of the other cronjob commands. Currently the options are php, curl, and wget. Choose which one works best, click save changes, and use that command.

= How do I remove my old cron job? =

For users of cPanel 1.0:

	1. Login
	2. Go to cPanel
	3. Scroll down to 'Cron Jobs'
	4. Look for 'Current Cron Jobs'
	5. Find the entry that relates to EAN (and click Delete). Confirm to Delete if asked.

NOTE: Our entry contains "wp-content/plugins/easy-automatic-newsletter/ean-schedule-newsletter.php"

NOTE: If these directions do not work for you, please contact your WEBMASTER or your HOSTING PROVIDER for technical assistance. Tell them "I'd like your help in removing a cron job."

= I keep getting after installing EAN "Fatal error: Cannot redeclare class FacebookApiException in /var/www/vhosts/family-oosten.com/httpdocs/blog/wp-content/plugins/easy-automatic-newsletter/facebook-sdk/base_facebook.php on line 107" =

The cause of this problem is another one of your plugins are conflicting with the FaceBook API. Currently a solution is being worked on to avoid this problem. As a temporary solution you can disable the conflicting plugin. One user reported "Comments Plus" plugin being a culprit.

IMPORTANT NOTE: FaceBook timeline removed due to various problems it caused different users...

= Where does the newsletter content come from? =

It's pulled from your blog posts for the past period (a day ago, a week, a month, the last 2 weeks, quarter, year)  to feed a new period's newsletter. You can specify which categories to pull from (so you could create a category called Newsletter). You can even include up to 20 Tweets from one Twitter account too.

= How many subscribers does this support? =

As many as your server will allow (likely a lot). Of course, the more you have (like 10,000) the longer it will take to send out the newsletters.

= What customizations do you offer? =

	* The frequency of your newsletters can be easily modified for daily, weekly, monthly, 2x/month, quarterly and yearly
	* The newsletter sent is a simple design allowing the content to get the attention.
	* You can customize the masthead with your own logo.
	* You can customize where the newsletter comes from and the subject line
	* You can customize all the messages sent and that appear when someone subscribes or unsubscribes
	* You can customize the titles that appear inside the newsletter
	* All the content of the newsletter comes from your blog posts (you can choose which categories)
	* You can include up to 20 Tweets from any Twitter user

= Is there any support? =

We have made every attempt to ensure that this plugin will simply work but as it is with going 'live' we cannot anticipate every unique scenario. There's a Feedback feature included in the plug-in and it's at the bottom of the menu. You can send us your comments, questions and feedback to us anonymously or include your email. If you want a chance for us to get back to you, please include your email address.

Prior to reporting an issue please ensure your WordPress installation updated to at least 3.3.1 (latest is preferred) and PHP is running at least 5.3.17. You can get your PHP version by using the phpinfo() plugin http://wordpress.org/extend/plugins/wordpress-php-info

= I love your plugin, but wish there was a way to white label it. =

Actually there will be. We are working on a Premium version of this plugin which will be available in a few weeks. It will be loaded with every single feature request that was provided by our users of the free plugin (approximately 30 new features). Follow us on Twitter @chiefatlarge to stay updated on when this Premium version will be available.

In addition, it would be greatly appreciated contributing to our beer fund :-)
https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VH8TK2Z79VM4E

While your at it why not give us a good rating:
http://wordpress.org/extend/plugins/easy-automatic-newsletter/

== How to use ==

NOTE: EAN employs the Core Control plugin by Dion Hulse. It is loaded from our installation package and will appear inside of Tools. Please do not remove or disable the function or else your newsletter will not be able to be scheduled

= Newsletter Post Settings =

1. Click on Settings (under the Easy Automatic Newsletter Lite tab in your Dashboard menu)
2. Configure the Email Frequency, Time to Send (in 24 hour time) and day you prefer to send your newsletter
3. Choose the category(ies) to send from.
4. Enter the number of characters (value must be numeric) to include for each blog post within your newsletter. Leave empty for all characters to be used.
5. Click the Save Changes button when done.
6. Proceed to Other Settings
7. Enter the URL for your logo image (this appears in the top of the Newsletter email)
8. Enter the From Name (person or organization name)
9. Enter the From Address (email address of person or organization)
10. Enter the Subject (the name of your newsletter is appropriate here)
11. Enter your Privacy Policy
12. Select "Link Tweets with newsletter" (optional)
13. Enter your Twitter account name (without the @)
14. Enter the number of Tweets to include (leave empty for the last 20)

NOTE: Unlike blog posts, the amount of Tweets included in your newsletter is irrespective of the date you Tweeted them. In other words, it is possible to send the same Tweets in a newsletter as were sent with a prior newsletter. To avoid that, keep Tweeting.

= Placing the Subscribe Widget =

From the list of Available Widgets, locate the widget called Easy Automatic Newsletter. Drag that to one of your sidebars to place the widget.

= Import Subscribers =

1. Click Import Subscribers to add a comma separated list of email addresses.
2. If you've added addresses click the check box to affirm that the recipients have authorized their subscription.

= Newsletter Status =

1. This is chock full of important information.
2. You can use this section to send test emails to the admin or a defined email address.

NOTE: If the message "No post to send" appears at the top it also means that you cannot send a test email either. If you see that, make a dummy blog post and publish it. You will now see at least 1 post to send.

= MESSAGES: =
= Subscription Messages =

Subscription Messages is where you can customize messages produced when someone subscribes and unsubscribes from your list.
At the top of this page are short codes you can use in the message body to insert information.
The default settings can be used as is or you can customize them to be in your 'voice'. Be sure to keep the gist of the message or else it will be confusing to subscribers.

= Opt-in Email Configuration =

This is the email received to the email address entered using the widget.
You can modify the subject and body but be sure to keep the gist of the message intact or else you can confuse your subscriber. Remember that it should be painless to subscribe to your newsletter.
Click Save when done.

= Confirmation Message =

After a subscriber receives the opt-in email and clicks on the confirmation link they are taken to a page on your website with the message you customize here.
Click Save if you edit the default message.
NOTE: Behind the scenes, once a subscriber clicks the link their status in the Subscribers section turns to Verified

= Unsubscribed Message =

When someone unsubscribes from your newsletter using the link at the bottom of the email newsletter they will be sent to a page on your site where the message you customize will appear.
As always, if you make an edit click the Save button.
NOTE: Behind the scenes, the subscriber's status will change. You are expected to abide by anti-spamming laws.

= Unsubscribed Email =

In addition to an onscreen confirmation of being unsubscribed, the user will receive one more email indicating that they've been unsubscribed. You can customize this message to be whatever you'd like.
Something short and polite will always work.
Be sure to click the Save button if you make an edits.

= Newsletter Titles =

You can edit the titles that appear inside the newsletter itself.
Click the Save button when done.

= ----- PRIOR EAN USERS ----- =

Since version 2.7 we have put the 'cron' system inside of WordPress so you do not need to configure anything outside of your Admin Panel. This is labeled WP Cron. Should you go with WP Cron then you must remove your cronjob (Refer to FAQ for removing cronjob) if configured.

--------------------------------

== Screenshots ==

1. Tab and Menu
2. Newsletter Settings

== Changelog ==

= v3.2.0 =
*Missing files addition

= v3.1.0 =
*Optimized plugin for WordPress 4.1
*Optimized Feedback form to improve technical support

= v2.9.0 =
*Optimized plugin for WordPress 4.x
*Optimized Feedback form to improve technical support

= v2.8.0 =

*Help Manual Modified
*The facebook timeline feature has been removed


= v2.7.2 =

*Help Manual Modified
*The facebook timeline feature has been removed

= v2.7.1 =

*Fixed wget URL
*Hid Cronjob related input fields in EAN Settings when WP Cron is selected to avoid confusion
*Help section added

= v2.7 =
*Added internal cron control (no need to use cPanel cron job)
*Fixed line break issue inside newsletter
*Added ability to customize titles inside newsletter
*Updated documentation inside plugin to reflect new features

= v2.6 =
*Unsubscribe link fix.
*Multiple cron issue resolve(Please check the command to set the cron job).

= v2.5 =
Security update.

= v2.4 =
Cron job settings & 2x/month frequency added.

= v2.3 =
Fix send date issue

= v2.2 =
Fix issues with year and month

= v2.1 =
* New feature: impose character limit for blog posts appearing in the newsletter (for long ones)
* New feature: provide email address to send test emails to (in addition to sending to Admin)
* Getting Started  upgrade to reflect new features

= v2.0 =
* Documentation upgrade.

= v1.0 =
* Initial release version.
