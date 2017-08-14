<?php
/*
Plugin Name: Easy Automatic Newsletter Lite v3.1.0
Description: Collect your blog posts for the past month and send them out at the top of a new month to subscribers. All automatically.
Version: 3.1.0
Author: PillarDev
*/
/*  Copyright 2011 Pillar
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'includes/ean-functions.php';
require_once 'facebook-sdk/facebook.php';


//To perform action while activating pulgin 
register_activation_hook( __FILE__, 'ean_activate');
register_deactivation_hook(__FILE__,'ean_deactivate');

//Create menu for configure page
add_action('admin_menu', 'ean_admin_actions');

//Add styles for admin page
add_action('admin_print_styles', 'ean_admin_style');


//Add the nedded styles & script for subscription box
add_action('wp_print_styles', 'ean_add_style');

add_action('init', 'ean_add_script');

//add shortcode
add_shortcode('ean', 'ean_show');

// register ean widget
add_action('widgets_init', create_function('', 'return register_widget("EanWidget");'));

add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
add_filter('wp_mail_from', 'ean_mail_from'); 
add_filter('wp_mail_from_name', 'ean_mail_from_name'); 	


function ean_mail_from() {
	$from_address = get_option('ean_from_address') ? get_option('ean_from_address') : get_bloginfo('admin_email');
	return $from_address;
}

function ean_mail_from_name() {
	$from_name = get_option('ean_from_name') ? stripslashes(get_option('ean_from_name')) : get_bloginfo('name');
	return $from_name;
}	

/** 
*Set the default options while activating the pugin
*/

function ean_activate() {
	ean_install();
}

/**
*Unset the ean schedules while deactivating the plugin
*/

function ean_deactivate() {
	ean_clear_cron();
}

/** Create menu for options page */

function ean_admin_actions() {
	add_menu_page('Easy Automatic Newsletter', 'Easy Automatic Newsletter Lite', 'manage_options', 'easy-automatic-newsletter', 'ean_admin');
	add_submenu_page('easy-automatic-newsletter', 'Settings', 'Settings', 'manage_options', 'easy-automatic-newsletter', 'ean_admin');
	add_submenu_page('easy-automatic-newsletter', 'Newsletter Status', 'Newsletter Status', 'manage_options', 'ean-admin-status','ean_status');
	add_submenu_page('easy-automatic-newsletter', 'Subscribers', 'Subscribers', 'manage_options', 'ean-admin-subscribers','ean_subscribers');
	add_submenu_page('easy-automatic-newsletter', 'Help Manual', 'Help Manual', 'manage_options', 'ean-help-manual', 'ean_help_manual');
	add_submenu_page('easy-automatic-newsletter', 'Feedback', 'Feedback', 'manage_options', 'ean-admin-feedback','ean_feedback');
	add_submenu_page('tools.php', 'Cron Tasks', 'Cron Tasks', 'manage_options', 'ean-cron-tasks', 'ean_cron_tasks');
}

function ean_cron_tasks() {
	if ( !current_user_can('manage_options') )
		wp_die( __('You do not have sufficient permission to access this page.') );
	include('admin/ean_admin-cron-tasks.php');
}
/** List subscribers in admin page */

function ean_help_manual() {
	if ( !current_user_can('manage_options') )
		wp_die( __('You do not have sufficient permission to access this page.') );
	include('admin/ean-admin-help-manual.php');		
}

function ean_getting_started() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.') );
	include('admin/ean-admin-getting-started.php');
}


/** Admin page to update settings of newsletter */

function ean_admin() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.') );
	include('admin/ean-admin-settings.php');
}

/** List subscribers in admin page */

function ean_subscribers() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.') );
	include('admin/ean-admin-subscribers.php');
}

/** Admin page to link twitter account */

function ean_tweets() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.') );
	include('admin/ean-admin-tweets.php');
}

/** Admin page to check the status of newsletter */

function ean_status() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.') );
	include('admin/ean-admin-status.php');
}

/** Admin page to import subscribers */

function ean_import() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.') );
	include('admin/ean-admin-import.php');
}


/** Admin page to update subscription mails & messages */

function ean_subscription_messages() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.') );
	include('admin/ean-admin-subscription-messages.php');
}

/** Admin page to provide feedback */

function ean_feedback() {
    if ( !current_user_can('manage_options') )
    	wp_die( __('You do not have sufficient permissions to access this page.') );
	include('admin/ean-admin-feedback.php');
}


/** Link the needed stylesheet for admin panel*/

function ean_admin_style() {	
	wp_enqueue_style('ean-admin-style', WP_PLUGIN_URL.'/easy-automatic-newsletter/css/ean-admin-style.css');
}

/** Link the needed stylesheet */

function ean_add_style() {
	wp_enqueue_style('ean-style', WP_PLUGIN_URL.'/easy-automatic-newsletter/css/style.css');
}

/** Link the needed script */

function ean_add_script() {
	if ( !is_admin() ){
		wp_enqueue_script('jquery');
	}
}


/** To show subscription box 
 * @return output
*/

function ean_show($show_powered_link) {
	return displaySubscriptionBox($show_powered_link);
}

/**
 * EanWidget Class
 */

class EanWidget extends WP_Widget {

    /** constructor */
    function EanWidget() {
        parent::WP_Widget(false, $name = 'Easy Automatic Newsletter Lite v2.7.2', array( 'description' => __( "Add subscription option on website") ));	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$show_powered_link = $instance['show_powered_link'];
		echo $before_widget;
        if ( $title )
		echo $before_title . $title . $after_title; 
		if (function_exists('ean_show')) echo ean_show($show_powered_link); 
		echo $after_widget; 
    }


    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['show_powered_link'] = $new_instance['show_powered_link'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
		$show_powered_link = $instance['show_powered_link'] ? 'checked="checked"' : '';
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><input class="checkbox" type="checkbox" <?php echo $show_powered_link; ?> id="<?php echo $this->get_field_id('show_powered_link'); ?>" name="<?php echo $this->get_field_name('show_powered_link'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_powered_link'); ?>"><?php _e('Show Powered link'); ?></label>
        <?php 
    }
} // class EanWidget
?>