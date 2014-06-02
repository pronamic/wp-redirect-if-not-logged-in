<?php
/*
Plugin Name: Redirect if not logged in
Plugin URI: http://pronamic.eu/wp-plugins/redirect-if-not-logged-in/
Description: Redirect vistors to an specific URL if they are not logged in to WordPress.

Version: 1.1.0
Requires at least: 3.5

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: redirect_if_not_logged_in
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-redirect-if-not-logged-in
*/

/**
 * Initialize
 */
function redirect_if_not_logged_in_init() {
	global $pagenow;

	$url = get_option( 'redirect_if_not_logged_in_url' );
	if ( empty( $url ) ) {
		$url = wp_login_url();
	}

	$redirect  = true;
	$redirect &= ! in_array( $pagenow, array( 'wp-login.php', 'wp-register.php' ) );
	$redirect &= ! is_admin();
	$redirect &= ! is_user_logged_in();
	$redirect &= ! ( defined( 'DOING_CRON' ) && DOING_CRON );
	$redirect &= ! ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	$redirect &= ( $url != home_url( $_SERVER['REQUEST_URI'] ) );

	if ( $redirect ) {
		wp_redirect( $url, 307 );

		exit;
	}
}

add_action( 'init', 'redirect_if_not_logged_in_init' );

/**
 * Admin intialize
 */
function redirect_if_not_logged_in_admin_init() {
	add_settings_section(
		'redirect_if_not_logged_in',
		'Redirect if not logged in',
		'__return_false',
		'reading'
	);

	add_settings_field(
		'redirect_if_not_logged_in_url',
		'URL',
		'redirect_if_not_logged_in_url_field',
		'reading',
		'redirect_if_not_logged_in',
		array( 'label_for' => 'redirect_if_not_logged_in_url' )
	);

	register_setting( 'reading', 'redirect_if_not_logged_in_url' );
}

add_action( 'admin_init', 'redirect_if_not_logged_in_admin_init' );

/**
 * URL field
 * s
 * @param array $args
 */
function redirect_if_not_logged_in_url_field( $args ) {
	$id = $args['label_for'];

	printf(
		'<input name="%s" id="%s" type="url" value="%s" class="regular-text code" />',
		$id,
		$id,
		get_option( 'redirect_if_not_logged_in_url' )
	);
}
