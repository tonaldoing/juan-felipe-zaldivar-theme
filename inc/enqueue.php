<?php
/**
 * Estilos y scripts.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'jfz_enqueue' );
function jfz_enqueue() {
	$css = JFZ_DIR . '/assets/css/style.css';
	wp_enqueue_style( 'jfz-style', JFZ_URI . '/assets/css/style.css', array(), file_exists( $css ) ? filemtime( $css ) : JFZ_VERSION );
	// El tema no usa JS en el front. Se quita jQuery y los emojis para no cargar nada de más.
	wp_dequeue_script( 'jquery' );
}

add_action( 'admin_enqueue_scripts', 'jfz_admin_enqueue' );
function jfz_admin_enqueue( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$js = JFZ_DIR . '/assets/js/admin-meta.js';
	wp_enqueue_script( 'jfz-admin-meta', JFZ_URI . '/assets/js/admin-meta.js', array(), file_exists( $js ) ? filemtime( $js ) : JFZ_VERSION, true );
	wp_add_inline_style( 'wp-admin', '.jfz-meta-group{margin:12px 0;padding:12px;border:1px solid #dcdcde;border-radius:4px}.jfz-meta-group h4{margin:0 0 8px}.jfz-meta-group label{display:block;margin:6px 0 2px;font-weight:600}.jfz-meta-group input[type=text],.jfz-meta-group input[type=url],.jfz-meta-group input[type=date],.jfz-meta-group input[type=time],.jfz-meta-group textarea{width:100%}.jfz-meta-group textarea{font-family:Georgia,serif;white-space:pre;min-height:220px}.jfz-meta-group[hidden]{display:none}.jfz-tipo-radio label{display:block;margin:4px 0}' );
}

/**
 * Preload de la fuente principal.
 */
add_action( 'wp_head', 'jfz_preload_fonts', 1 );
function jfz_preload_fonts() {
	printf( '<link rel="preload" href="%s/assets/fonts/literata-latin.woff2" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( JFZ_URI ) );
}

/**
 * Sin emojis ni oEmbed discovery: menos peso.
 */
add_action( 'init', 'jfz_clean_head' );
function jfz_clean_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
}
