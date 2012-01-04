<?php
/*
Plugin Name: ZigOut
Plugin URI: http://www.zigpress.com/wordpress/plugins/zigout/
Description: Puts the famous OUT Campaign's Atheist "A" on your site.
Version: 0.1.1
Author: ZigPress
Requires at least: 3.1
Tested up to: 3.3.1
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


/*
ZigPress PHP code uses Whitesmiths indent style: http://en.wikipedia.org/wiki/Indent_style#Whitesmiths_style
*/


# DEFINE WIDGET BY EXTENDING CORE WIDGET CLASS


class widget_zigout extends WP_Widget
	{
	public $PluginFolder;


	function widget_zigout()
		{
		global $wp_version;
		if (version_compare(phpversion(), '5.2.4', '<')) $this->AutoDeactivate('ZigOut requires PHP 5.2.4 or newer and has now deactivated itself. Please update your server before reactivating.'); 
		if (version_compare($wp_version, '3.1', '<')) $this->AutoDeactivate('ZigOut requires WordPress 3.0 or newer and has now deactivated itself. Please update your installation before reactivating.'); 
		$widget_opts = array('description' => 'Add the Atheist A to your sidebar.');
		parent::WP_Widget(false, $name = 'ZigOut', $widget_opts);
		add_action('wp_head', array($this, 'ActionWpHead'));
		add_filter('plugin_row_meta', array($this, 'FilterPluginRowMeta'), 10, 2 );
		$this->PluginFolder = get_bloginfo('url') . '/' . PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)) . '/';
		}


	public function AutoDeactivate($strMessage)
		{
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
		deactivate_plugins(__FILE__);
		wp_die($strMessage); 
		}


	function update($new_instance, $old_instance)
		{
		global $wpdb;
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['shiny'] = (strip_tags($new_instance['shiny']) == 'yes') ? 'yes' : 'no';
		$instance['caption'] = (strip_tags($new_instance['caption']) == 'yes') ? 'yes' : 'no';
		return $instance;
		}


	function form($instance)
		{
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


	function widget($args, $instance)
		{
		extract($args);
		echo $before_widget;
		$title = apply_filters('widget_title', $instance['title']);
		echo ($title) ? $before_title . $title . $after_title : '';
		$strImage = ($instance['shiny'] == 'yes') ? 'A-100-v3.png' : 'scarlet_A.png';
		?>
		<div class="zigout_wrapper">
		<a class="zigout_link" href="http://outcampaign.org/" target="_blank">
		<img class="zigout_image" src="<?php echo $this->PluginFolder?>images/<?php echo $strImage?>" alt="The Out Campaign: Scarlet Letter of Atheism" />
		</a>
		<?php
		if ($instance['caption']) echo '<div class="zigout_caption">Join the OUT campaign</div>';
		?>
		</div>
		<?php
		echo $after_widget;
		}


	function ActionWpHead()
		{
		?>
		<!-- BEGIN ZigOut HEAD Insert -->
		<link rel="stylesheet" href="<?php echo $this->PluginFolder?>css/zigout.css?<?php echo rand(1000,9999)?>" type="text/css" />
		<!-- END ZigOut HEAD Insert -->
		<?php
		}


	function FilterPluginRowMeta($links, $file) 
		{
		$plugin = plugin_basename(__FILE__);
		if ($file == $plugin) return array_merge($links, array(
			'<a target="_blank" href="http://www.zigpress.com/donations/">Donate</a>', 
			'<a target="_blank" href="http://outcampaign.org/">The OUT Campaign</a>'
			));
		return $links;
		}


	} # END OF CLASS


add_action('widgets_init', create_function('', 'return register_widget("widget_zigout");'));


# EOF
