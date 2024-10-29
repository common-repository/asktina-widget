<?php
/**
 * @package AskTina_Widget
 * @version 1.0
 */
/*
Plugin Name: AskTina Widget
Plugin URI: https://asktina.io
Description: Add the AskTina chat widget to your Wordpress site
Author: James Bradley
Version: 1.3
Author URI: https://www.jbrew.co.uk/
*/

add_action( 'admin_menu', 'asktina_add_admin_menu' );
add_action( 'admin_init', 'asktina_settings_init' );


function asktina_add_admin_menu(  ) {
	add_options_page( 'AskTina', 'AskTina', 'manage_options', 'asktina', 'asktina_options_page' );
}


function asktina_settings_init(  ) {
	register_setting( 'pluginPage', 'asktina_settings' );

	add_settings_section(
		'asktina_section_general',
		__( 'General', 'wordpress' ),
		'asktina_settings_general_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'asktina_widget_id',
		__( 'ID', 'wordpress' ),
		'asktina_widget_id_render',
		'pluginPage',
		'asktina_section_general'
	);

	add_settings_section(
		'asktina_section_visbility',
		__( 'Visibility Configuration', 'wordpress' ),
		'asktina_settings_visbility_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'asktina_visbility_homepage',
		__( 'Show on homepage', 'wordpress' ),
		'asktina_widget_visbility_homepage_render',
		'pluginPage',
		'asktina_section_visbility'
	);

	add_settings_field(
		'asktina_visbility_users',
		__( 'Show for logged in users', 'wordpress' ),
		'asktina_widget_visbility_users_render',
		'pluginPage',
		'asktina_section_visbility'
	);

	add_settings_field(
		'asktina_visbility_types',
		__( 'Show on post/page types', 'wordpress' ),
		'asktina_widget_visbility_types_render',
		'pluginPage',
		'asktina_section_visbility'
	);

	add_settings_field(
		'asktina_widget_visbility_categories',
		__( 'Show for categories', 'wordpress' ),
		'asktina_widget_visbility_categories_render',
		'pluginPage',
		'asktina_section_visbility'
	);

	add_settings_field(
		'asktina_widget_visbility_tags',
		__( 'Show for tags', 'wordpress' ),
		'asktina_widget_visbility_tags_render',
		'pluginPage',
		'asktina_section_visbility'
	);
}


function asktina_widget_id_render() {
	$options = get_option( 'asktina_settings' );
	?>
	<input
		type='text'
		name='asktina_settings[asktina_widget_id]'
		value='<?php echo $options['asktina_widget_id']; ?>'>
	<?php
}

function asktina_widget_visbility_homepage_render() {
	$options = get_option( 'asktina_settings' );
	?>
	<input
		type='checkbox'
		name='asktina_settings[asktina_widget_visbility_homepage]'
		value='1'
		<?php checked( '1', $options['asktina_widget_visbility_homepage'], true ); ?>
	/>
	<?php
}

function asktina_widget_visbility_users_render() {
	$options = get_option( 'asktina_settings' );
	?>
	<input
		type='checkbox'
		name='asktina_settings[asktina_widget_visbility_users]'
		value='1'
		<?php checked( '1', $options['asktina_widget_visbility_users'], true ); ?>
	/>
	<?php
}

function asktina_widget_visbility_types_render() {
	$options = get_option( 'asktina_settings' );
	?>
	<input
		type='checkbox'
		name='asktina_settings[asktina_widget_visbility_types][posts]'
		value='1'
		<?php checked( '1', $options['asktina_widget_visbility_types']['posts'], true ); ?>
	/>
	<label>Posts</label>
	<br>
	<input
		type='checkbox'
		name='asktina_settings[asktina_widget_visbility_types][pages]'
		value='1'
		<?php checked( '1', $options['asktina_widget_visbility_types']['pages'], true ); ?>
	/>
	<label>Pages</label>
	<?php
}

function asktina_widget_visbility_categories_render() {
	$options = get_option( 'asktina_settings' );
	$categories = get_categories(array('hide_empty' => false));

	foreach ($categories as &$value) {
		?>
		<input
			type='checkbox'
			name='asktina_settings[asktina_widget_visbility_categories][<?php echo $value->term_id ?>]'
			value='1'
			<?php checked( '1', $options['asktina_widget_visbility_categories'][$value->term_id], true ); ?>
		/>
		<label><?php echo $value->name ?></label>
		<br>
		<?php
	}
}

function asktina_widget_visbility_tags_render() {
	$options = get_option( 'asktina_settings' );
	$tags = get_tags(array('hide_empty' => false));

	foreach ($tags as &$value) {
		?>
		<input
			type='checkbox'
			name='asktina_settings[asktina_widget_visbility_tags][<?php echo $value->term_id ?>]'
			value='1'
			<?php checked( '1', $options['asktina_widget_visbility_tags'][$value->term_id], true ); ?>
		/>
		<label><?php echo $value->name ?></label>
		<br>
		<?php
	}
}


function asktina_settings_general_section_callback(  ) {
	echo __( 'General widget configuration options', 'wordpress' );
}

function asktina_settings_visbility_section_callback(  ) {
	echo __( 'Configure the visbility of the widget. It will be displayed if any of the following rules match the current page.', 'wordpress' );
}


function asktina_options_page(  ) {
	?>
	<div class="wrap">
		<form action='options.php' method='post'>

			<h1>AskTina</h1>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
	</div>
	<?php
}

function widget_id() {
	$options = get_option( 'asktina_settings' );
	$old = get_option( 'asktina_widget_id' );
	$new = $options['asktina_widget_id'];
	if ($new) return $new;
	return $old;
}

function widget_html() {
	global $post;
	global $page;
	$options = get_option( 'asktina_settings' );
	$widget = '<script type="text/javascript" src="https://widget.asktina.io/latest.js" data-id="' . widget_id() . '"></script>';

	if (is_single()) {
		if ($options['asktina_widget_visbility_types']['posts'] === '1') $display = true;

		$categories = wp_get_post_categories($post->ID);
		foreach ($categories as &$value) {
			if ($options['asktina_widget_visbility_categories'][$value] === '1') $display = true;
		}

		$tags = wp_get_post_tags($post->ID);
		foreach ($tags as &$value) {
			if ($options['asktina_widget_visbility_categories'][$value] === '1') $display = true;
		}
	}

	if (is_page()) {
		if ($options['asktina_widget_visbility_types']['pages'] === '1') $display = true;
	}

	if (is_user_logged_in() && $options['asktina_widget_visbility_users']) $display = true;
	if (is_home() && $options['asktina_widget_visbility_homepage']) $display = true;

	if ($display) echo $widget;
}

add_action( 'wp_footer', 'widget_html' );
?>
