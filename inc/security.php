<?php
/**
 * Endurecimiento básico: sin XML-RPC, sin versión, sin archivos de autor ni enumeración de usuarios.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Sitio de un solo autor: los archivos de autor redirigen a la portada.
 */
add_action( 'template_redirect', 'jfz_redirect_author' );
function jfz_redirect_author() {
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}

/**
 * Bloquea ?author=N y el endpoint REST de usuarios para visitantes.
 */
add_action( 'init', 'jfz_block_author_enum' );
function jfz_block_author_enum() {
	if ( ! is_admin() && isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_filter( 'rest_endpoints', 'jfz_rest_hide_users' );
function jfz_rest_hide_users( $endpoints ) {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}
	unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	return $endpoints;
}

/**
 * Mensaje de login genérico.
 */
add_filter( 'login_errors', fn() => __( 'Los datos ingresados no son correctos.', 'juan-felipe-zaldivar' ) );
