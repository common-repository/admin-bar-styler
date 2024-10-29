<?php
/**
 * Plugin Name: Admin Bar Styler
 * Description: Colorize the admin bar to match your site style, or make a visual distinction between different environments such as DEV, QA, UAT, PROD.
 * Version: 1.2.0
 * Author: John Alarcon
 * Author URI: https://johnalarcon.com
 * Text Domain: alar-admin-bar-styler
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * See http://www.gnu.org/licenses/gpl-2.0.txt.
 *
 */

class AlarAdminBarStyler {

	/**
	 * Just your basic constructor.
	 *
	 */
	public function __construct() {
		register_activation_hook(__FILE__, array($this, 'activation'));
		register_uninstall_hook(__FILE__, array('AlarEnvironmentIndicator', 'uninstallation'));
		$this->init();
	}

	/**
	 * Initialize the plugin.
	 *
	 */
	public function init() {
		// Add a Settings link to the core plugin admin page.
		add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'add_action_link'));
		// Register settings and admin menu.
		add_action('admin_init', 			array($this, 'register_settings'));
		add_action('admin_menu', 			array($this, 'register_admin_menu'));
		// Enqueued styles (and script for admin).
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles'));
		add_action('wp_enqueue_scripts', 	array($this, 'enqueue_scripts_and_styles'));
		// Inlined styles.
		add_action('admin_enqueue_scripts', array($this, 'inline_styles'));
		add_action('wp_enqueue_scripts', 	array($this, 'inline_styles'));
		// Settings link in core plugin view.
		add_action('admin_bar_menu', 		array($this, 'add_label_to_admin_bar'), 1);
	}

	/**
	 * Register the plugin's options variable.
	 */
	public function register_settings() {
		register_setting('admin_bar_styler', 'admin_bar_styler', array());
	}

	/**
	 * Register admin menu item as a submenu under the core Settings item.
	 *
	 */
	public function register_admin_menu() {
		add_submenu_page(
				'themes.php',
				__('Admin Bar', 'alar-admin-bar-styler'),
				__('Admin Bar', 'alar-admin-bar-styler'),
				'manage_options',
				'admin-bar-styler',
				array($this, 'render_admin_page')
				);
	}

	/**
	 * Add a Settings link to the plugin admin page.
	 *
	 * @param array $links
	 * @return array $links
	 *
	 */
	public function add_action_link($links) {
		$links[] = '<a href="'. esc_url(get_admin_url(null, 'themes.php?page=admin-bar-styler')) .'">'.__('Settings', 'alar-admin-bar-styler').'</a>';
		return $links;
	}

	/**
	 * Render the settings page.
	 *
	 */
	public function render_admin_page() {

		$options = get_option('admin_bar_styler', array());
		echo '<div class="wrap">';
		echo '<h1>'.__('Admin Bar Styler', 'alar-admin-bar-styler').'</h1>';
		settings_errors();
		echo '<form method="post">';
		settings_fields('admin_bar_styler');
		wp_nonce_field('admin_bar_styler_nonce');
		echo '<table class="form-table">'."\n";
		echo '<tr valign="top">'."\n";
		echo '<th scope="row"><label for="admin-bar-styler-label">'.__('Text Label', 'alar-admin-bar-styler').'</label></th>'."\n";
		echo '<td>';
		echo '<input type="text" name="admin_bar_styler[label]" id="admin-bar-styler-label" value="'.esc_attr($options['label']).'" /><br />';
		echo '<span class="description"><label for="admin-bar-styler-label">'.__('Optional label shown at upper left', 'alar-admin-bar-styler').'</label></span>';
		echo '</td>'."\n";
		echo '</tr>'."\n";
		echo '<tr valign="top">'."\n";
		echo '<th scope="row"><label for="admin-bar-styler-bg-color">'.__('Admin Bar Color', 'alar-admin-bar-styler').'</label></th>'."\n";
		echo '<td>';
		echo '<input type="color" name="admin_bar_styler[bg_color]" id="admin-bar-styler-bg-color" value="'.esc_attr($options['bg_color']).'" /><br />';
		echo '<span class="description"><label for="admin-bar-styler-bg-color">'.__('Primay color for admin bar', 'alar-admin-bar-styler').'</label></span>';
		echo '</td>'."\n";
		echo '</tr>'."\n";
		echo '<tr valign="top">'."\n";
		echo '<th scope="row"><label for="admin-bar-styler-hover-color">'.__('Admin Bar Hover', 'alar-admin-bar-styler').'</label></th>'."\n";
		echo '<td>';
		echo '<input type="color" name="admin_bar_styler[hover_color]" id="admin-bar-styler-hover-color" value="'.esc_attr($options['hover_color']).'" /><br />';
		echo '<span class="description"><label for="admin-bar-styler-hover-color">'.__('Hover color for admin bar', 'alar-admin-bar-styler').'</label></span>';
		echo '</td>'."\n";
		echo '</tr>'."\n";
		echo '<tr valign="top">'."\n";
		echo '<th scope="row"><label for="admin-bar-styler-font-color">'.__('Font Color', 'alar-admin-bar-styler').'</label></th>'."\n";
		echo '<td>';
		echo '<input type="color" name="admin_bar_styler[font_color]" id="admin-bar-styler-font-color" value="'.esc_attr($options['font_color']).'" /><br />';
		echo '<span class="description"><label for="admin-bar-styler-font-color">'.__('Color for menu items', 'alar-admin-bar-styler').'</label></span>';
		echo '</td>'."\n";
		echo '</tr>'."\n";
		echo '<tr valign="top">'."\n";
		echo '<th scope="row"><label for="admin-bar-styler-presets">'.__('Preset Schemes', 'alar-admin-bar-styler').'</label></th>'."\n";
		echo '<td>';
		echo '<div style="margin:20px 0;">';
		echo '<span class="admin-bar-styler-preset red">'.__('Text', 'alar-admin-bar-styler').'</span>';
		echo '<span class="admin-bar-styler-preset yellow">'.__('Text', 'alar-admin-bar-styler').'</span>';
		echo '<span class="admin-bar-styler-preset blue">'.__('Text', 'alar-admin-bar-styler').'</span>';
		echo '<span class="admin-bar-styler-preset green">'.__('Text', 'alar-admin-bar-styler').'</span>';
		echo '<span class="admin-bar-styler-preset orange">'.__('Text', 'alar-admin-bar-styler').'</span>';
		echo '<span class="admin-bar-styler-preset purple">'.__('Text', 'alar-admin-bar-styler').'</span>';
		echo '</div>';
		echo '<span class="description">'.__('Click to load a preset', 'alar-admin-bar-styler').'</span></td>'."\n";
		echo '</tr>'."\n";
		echo '</table>'."\n";
		submit_button();
		echo '</form>'."\n";
		echo '</div>'."\n";
	}

	/**
	 * Load scripts and styles for the plugin.
	 */
	public function enqueue_scripts_and_styles() {
		GLOBAL $plugin_page;
		wp_enqueue_style('admin-bar-styler', plugins_url('/styles/styles.css', __FILE__));
		if (strstr($plugin_page, 'admin-bar-styler')) {
			wp_enqueue_script('admin-bar-styler', plugins_url('/scripts/scripts.js', __FILE__), array('jquery'));
		}
	}

	/**
	 * Add a text label to the admin bar.
	 *
	 * @param array $wp_admin_bar
	 */
	public function add_label_to_admin_bar($wp_admin_bar) {

		$options = get_option('admin_bar_styler');
		if (empty($options['label'])) {
			return;
		}
		$args = array(
				'id'    => 'admin-bar-styler',
				'title' => esc_html($options['label']),
				'href'  => '',
				'meta'  => array('class' => 'admin-bar-styler-label')
		);
		$wp_admin_bar->add_node($args);
	}

	/**
	 * Inline styles.
	 *
	 */
	public function inline_styles() {
		// Ensures the admin bar reflects latest values.
		if (isset($_POST['admin_bar_styler'])) {
			$this->update_settings();
		}
		// Inline the CSS.
		$options = get_option('admin_bar_styler', array());
		$css = 'div#wpadminbar {background: '.$options['bg_color'].' !important;}';
		$css .= '#wpadminbar .ab-top-menu > li.hover > .ab-item,';
		$css .= '#wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus,';
		$css .= '#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item,';
		$css .= '#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus {color: '.$options['font_color'].' !important;background: '.$options['hover_color'].' !important;}';
		$css .= '#wpadminbar .ab-top-menu > li > a,';
		$css .= '#wpadminbar .ab-top-menu > li > a span,';
		$css .= '#wpadminbar .ab-item::before,';
		$css .= '#wpadminbar .ab-icon::before,';
		$css .= '#wpadminbar #adminbarsearch::before {color: '.$options['font_color'].' !important;}';
		$css .= '#wpadminbar .ab-top-menu > li.admin-bar-styler-label > div.ab-empty-item {color: '.$options['font_color'].' !important;font-size:18pt;font-weight:bold;}';
		wp_add_inline_style('admin-bar-styler', $css);
	}

	/**
	 * Update the plugin's settings.
	 */
	public function update_settings() {
		// Yeah, no.
		if (!isset($_POST['_wpnonce'])) {
			return false;
		}
		// Nonce cool? Grab options as a whitelist.
		if (wp_verify_nonce((string)$_POST['_wpnonce'], 'admin_bar_styler_nonce')) {
			$options['label'] = sanitize_text_field($_POST['admin_bar_styler']['label']);
			$options['bg_color'] = sanitize_text_field($_POST['admin_bar_styler']['bg_color']);
			$options['hover_color'] = sanitize_text_field($_POST['admin_bar_styler']['hover_color']);
			$options['font_color'] = sanitize_text_field($_POST['admin_bar_styler']['font_color']);
			update_option('admin_bar_styler', $options);
		}
	}

	/**
	 * Initialize (on install) or get (on reactivate) plugin options.
	 */
	public function activation() {
		$options = array(
				'label'			=> '',
				'bg_color'		=> '#23282d',
				'font_color'	=> '#cccccc',
				'hover_color'	=> '#444444',
		);
		update_option('admin_bar_styler', $options);
	}

	/**
	 * Delete plugin options on plugin deletion.
	 */
	public static function uninstallation() {
		delete_option('admin_bar_styler');
	}

}

new AlarAdminBarStyler;