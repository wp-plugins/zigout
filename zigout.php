<?php
/*
Plugin Name: ZigOut
Plugin URI: http://www.zigpress.com/plugins/zigout/
Description: Puts the famous OUT Campaign's Atheist "A" on your site.
Version: 0.2.2
Author: ZigPress
Requires at least: 3.5
Tested up to: 3.9
Author URI: http://www.zigpress.com/
License: GPLv2
*/


/*
Copyright (c) 2011-2012 ZigPress

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation Inc, 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/


# DEFINE WIDGET BY EXTENDING CORE WIDGET CLASS


if (!class_exists('widget_zigout')) {


	class widget_zigout extends WP_Widget
	{
	
	
		private $plugin_folder;
	
	
		function widget_zigout() {
			$this->plugin_folder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
			global $wp_version;
			if (version_compare(phpversion(), '5.2.4', '<')) wp_die('ZigOut requires PHP 5.2.4 or newer. Please update your server.'); 
			if (version_compare($wp_version, '3.1', '<')) wp_die('ZigOut requires WordPress 3.1 or newer. Please update your installation.'); 
			$widget_opts = array('description' => 'Add the Atheist A to your sidebar.');
			parent::WP_Widget(false, $name = 'ZigOut', $widget_opts);
			add_action('wp_enqueue_scripts', array($this, 'action_wp_enqueue_scripts'));
			add_filter('plugin_row_meta', array($this, 'filter_plugin_row_meta'), 10, 2 );
		}
	
	
		function update($new_instance, $old_instance) {
			global $wpdb;
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['shiny'] = (strip_tags($new_instance['shiny']) == 'yes') ? 'yes' : 'no';
			$instance['caption'] = (strip_tags($new_instance['caption']) == 'yes') ? 'yes' : 'no';
			return $instance;
		}
	
	
		function form($instance) {
			?>
			<p>Title (leave empty to hide): <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></p>
			<p><select class="widefat" id="<?php echo $this->get_field_id('shiny'); ?>" name="<?php echo $this->get_field_name('shiny'); ?>">
			<option value="yes" <?php if (esc_attr($instance['shiny']) == 'yes') { echo('selected="selected"'); } ?> >Shiny version</option>
			<option value="no" <?php if (esc_attr($instance['shiny']) != 'yes') { echo('selected="selected"'); } ?> >Plain version</option>
			</select></p>
			<p><select class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>">
			<option value="yes" <?php if (esc_attr($instance['caption']) == 'yes') { echo('selected="selected"'); } ?> >Show caption below image</option>
			<option value="no" <?php if (esc_attr($instance['caption']) != 'yes') { echo('selected="selected"'); } ?> >Do not show caption</option>
			</select></p>
			<?php
		}
	
	
		function widget($args, $instance) {
			extract($args);
			echo $before_widget;
			$title = apply_filters('widget_title', $instance['title']);
			echo ($title) ? $before_title . $title . $after_title : '';
			$strImage = ($instance['shiny'] == 'yes') ? 'A-100-v3.png' : 'scarlet_A.png';
			?>
			<div class="zigout_image">
			<a class="zigout_link" href="http://outcampaign.org/" target="_blank">
			<img class="zigout_image" src="<?php echo $this->plugin_folder?>/images/<?php echo $strImage?>" alt="The Out Campaign: Scarlet Letter of Atheism" />
			</a>
			</div><!--/zigout_image-->
			<?php
			if ($instance['caption'] == 'yes') echo '<div class="zigout_caption"><a href="http://outcampaign.org/" target="_blank">Join the OUT campaign</a></div>';
			?>
			<?php
			echo $after_widget;
		}
	
	
		function action_wp_enqueue_scripts() {
			wp_enqueue_style('zigout', $this->plugin_folder . '/css/zigout.css', array(), rand(), 'all');
		}
	
	
		function filter_plugin_row_meta($links, $file) {
			$plugin = plugin_basename(__FILE__);
			if ($file == $plugin) return array_merge($links, array(
				'<a target="_blank" href="http://www.zigpress.com/donations/">Donate</a>', 
				'<a target="_blank" href="http://outcampaign.org/">The OUT Campaign</a>'
			));
			return $links;
		}
	
	
	} # END OF CLASS


} else {
	wp_die('Namespace clash! Class widget_zigout already exists.');
}


add_action('widgets_init', create_function('', 'return register_widget("widget_zigout");'));


# EOF
