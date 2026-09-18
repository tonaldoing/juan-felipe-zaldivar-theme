<?php
/**
 * Soportes del tema, menús, tamaños de imagen.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'jfz_setup' );
function jfz_setup() {
	load_theme_textdomain( 'juan-felipe-zaldivar', JFZ_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	// Portadas opcionales (Libros y Cine).
	set_post_thumbnail_size( 640, 640, false );
	add_image_size( 'jfz-portada', 480, 720, false );

	register_nav_menus(
		array(
			'principal' => __( 'Menú principal', 'juan-felipe-zaldivar' ),
			'pie'       => __( 'Menú del pie', 'juan-felipe-zaldivar' ),
		)
	);
}

/**
 * Sin comentarios en todo el sitio.
 */
add_action( 'init', 'jfz_disable_comments' );
function jfz_disable_comments() {
	foreach ( get_post_types() as $type ) {
		if ( post_type_supports( $type, 'comments' ) ) {
			remove_post_type_support( $type, 'comments' );
			remove_post_type_support( $type, 'trackbacks' );
		}
	}
}
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_filter( 'comments_array', '__return_empty_array', 10 );

add_action( 'admin_menu', 'jfz_remove_comments_menu' );
function jfz_remove_comments_menu() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_bar_menu', 'jfz_remove_comments_bar', 999 );
function jfz_remove_comments_bar( $bar ) {
	$bar->remove_node( 'comments' );
}

/**
 * Largo del extracto en la home.
 */
add_filter( 'excerpt_length', fn() => 45, 999 );
add_filter( 'excerpt_more', fn() => '…' );

/**
 * Menú de respaldo cuando todavía no hay ninguno asignado.
 */
function jfz_fallback_menu() {
	$items = array(
		home_url( '/' )                         => __( 'Inicio', 'juan-felipe-zaldivar' ),
		jfz_page_url_by_template( 'page-templates/indice.php', 'indice' ) => __( 'Índice', 'juan-felipe-zaldivar' ),
		get_post_type_archive_link( 'evento' )  => __( 'Eventos', 'juan-felipe-zaldivar' ),
		jfz_page_url_by_slug( 'sobre-mi' )      => __( 'Sobre mí', 'juan-felipe-zaldivar' ),
	);
	echo '<ul class="nav__list">';
	foreach ( $items as $url => $label ) {
		if ( ! $url ) {
			continue;
		}
		printf( '<li class="nav__item"><a class="nav__link" href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Al activar el tema: permalinks por nombre de entrada, zona horaria y rewrites de eventos/tipos.
 */
add_action( 'after_switch_theme', 'jfz_on_activation', 20 );
function jfz_on_activation() {
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	if ( ! get_option( 'timezone_string' ) && ! (float) get_option( 'gmt_offset' ) ) {
		update_option( 'timezone_string', 'America/Argentina/Buenos_Aires' );
	}
	jfz_register_tipo();
	jfz_register_evento();
	flush_rewrite_rules();
}
