<?php
/**
 * Meta básicos: descripción, Open Graph, feeds por tipo.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'jfz_meta_tags', 5 );
function jfz_meta_tags() {
	$title = wp_get_document_title();
	$desc  = get_bloginfo( 'description' );
	$url   = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	$type  = 'website';
	$image = '';

	if ( is_singular() ) {
		$post = get_queried_object();
		$type = 'article';
		$url  = get_permalink( $post );
		if ( has_excerpt( $post ) ) {
			$desc = get_the_excerpt( $post );
		} else {
			$lines = jfz_plain_lines( $post->post_content );
			$desc  = wp_trim_words( implode( ' ', $lines ), 30, '…' );
		}
		if ( has_post_thumbnail( $post ) ) {
			$image = get_the_post_thumbnail_url( $post, 'large' );
		}
	} elseif ( is_tax( 'tipo' ) ) {
		$term = get_queried_object();
		$desc = $term->description ?: $desc;
	}

	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:locale" content="es_AR">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="' . ( $image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
}

/**
 * Feeds por tipo de texto, anunciados en el head para lectores RSS.
 */
add_action( 'wp_head', 'jfz_feed_links', 3 );
function jfz_feed_links() {
	foreach ( jfz_tipos() as $slug => $label ) {
		$term = get_term_by( 'slug', $slug, 'tipo' );
		if ( $term ) {
			printf( '<link rel="alternate" type="application/rss+xml" title="%s » %s" href="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ), esc_attr( jfz_tipo_plural( $slug ) ), esc_url( get_term_feed_link( $term->term_id, 'tipo' ) ) );
		}
	}
}

/**
 * Separador del título del documento.
 */
add_filter( 'document_title_separator', fn() => '·' );

/**
 * Los eventos y páginas no van al feed principal; el feed es el flujo de textos.
 */
add_filter( 'pre_get_posts', 'jfz_feed_only_posts' );
function jfz_feed_only_posts( $query ) {
	if ( $query->is_feed() && $query->is_main_query() && ! $query->get( 'post_type' ) ) {
		$query->set( 'post_type', 'post' );
	}
	return $query;
}
