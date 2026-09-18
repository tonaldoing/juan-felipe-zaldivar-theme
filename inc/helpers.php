<?php
/**
 * Funciones auxiliares usadas por los templates.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Orden fijo de los tipos de texto (slug => etiqueta).
 */
function jfz_tipos() {
	return array(
		'poema'      => __( 'Poema', 'juan-felipe-zaldivar' ),
		'traduccion' => __( 'Traducción', 'juan-felipe-zaldivar' ),
		'libros'     => __( 'Libros', 'juan-felipe-zaldivar' ),
		'cine'       => __( 'Cine', 'juan-felipe-zaldivar' ),
		'nota'       => __( 'Nota', 'juan-felipe-zaldivar' ),
	);
}

/**
 * Plural para encabezados de listados.
 */
function jfz_tipo_plural( $slug ) {
	$plural = array(
		'poema'      => __( 'Poemas', 'juan-felipe-zaldivar' ),
		'traduccion' => __( 'Traducciones', 'juan-felipe-zaldivar' ),
		'libros'     => __( 'Libros', 'juan-felipe-zaldivar' ),
		'cine'       => __( 'Cine', 'juan-felipe-zaldivar' ),
		'nota'       => __( 'Notas', 'juan-felipe-zaldivar' ),
	);
	return $plural[ $slug ] ?? ucfirst( $slug );
}

/**
 * Término "tipo" de una entrada. Devuelve WP_Term o null.
 */
function jfz_get_tipo( $post = null ) {
	$terms = get_the_terms( $post, 'tipo' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}
	return $terms[0];
}

function jfz_get_tipo_slug( $post = null ) {
	$tipo = jfz_get_tipo( $post );
	return $tipo ? $tipo->slug : 'nota';
}

/**
 * Meta de entrada, con escape resuelto por quien la imprime.
 */
function jfz_meta( $key, $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	return get_post_meta( $post_id, '_jfz_' . $key, true );
}

/**
 * Vista previa para el listado. Para poemas, los primeros versos; para prosa, el extracto.
 */
function jfz_entry_preview( $post = null, $lines = 4 ) {
	$post = get_post( $post );
	if ( has_excerpt( $post ) ) {
		return '<p>' . esc_html( get_the_excerpt( $post ) ) . '</p>';
	}
	$tipo = jfz_get_tipo_slug( $post );
	if ( in_array( $tipo, array( 'poema', 'traduccion' ), true ) ) {
		$text  = jfz_plain_lines( $post->post_content );
		$total = count( $text );
		$text  = array_slice( $text, 0, $lines );
		$out   = implode( "\n", $text );
		if ( $total > $lines ) {
			$out .= "\n…";
		}
		return '<pre class="verso verso--preview">' . esc_html( $out ) . '</pre>';
	}
	return '<p>' . esc_html( wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 45, '…' ) ) . '</p>';
}

/**
 * Convierte contenido de bloques en líneas de texto plano (sin líneas vacías).
 */
function jfz_plain_lines( $content ) {
	$content = preg_replace( '/<!--.*?-->/s', '', $content );
	$content = preg_replace( '/<br\s*\/?>/i', "\n", $content );
	$content = preg_replace( '/<\/(p|div|pre|h[1-6]|li)>/i', "\n", $content );
	$content = html_entity_decode( wp_strip_all_tags( $content ), ENT_QUOTES, 'UTF-8' );
	$lines   = array_map( 'rtrim', explode( "\n", $content ) );
	return array_values( array_filter( $lines, fn( $l ) => trim( $l ) !== '' ) );
}

/**
 * Fecha legible en español ("18 de septiembre de 2026"), sin depender del idioma de WordPress.
 * Acepta un post (o nada, para el actual) o una fecha "Y-m-d".
 */
function jfz_fecha( $timestamp_or_post = null ) {
	if ( is_string( $timestamp_or_post ) && preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $timestamp_or_post, $m ) ) {
		return (int) $m[3] . ' de ' . jfz_mes( (int) $m[2] ) . ' de ' . $m[1];
	}
	$post = get_post( $timestamp_or_post );
	if ( ! $post ) {
		return '';
	}
	return (int) get_the_date( 'j', $post ) . ' de ' . jfz_mes( (int) get_the_date( 'n', $post ) ) . ' de ' . get_the_date( 'Y', $post );
}

/**
 * Nombre del mes en español. Con $corto, abreviado ("sep").
 */
function jfz_mes( $n, $corto = false ) {
	$meses = array( 1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' );
	$mes   = $meses[ $n ] ?? '';
	return $corto ? mb_substr( $mes, 0, 3 ) : $mes;
}

/**
 * URL de la primera página que usa un template dado, con respaldo por slug.
 */
function jfz_page_url_by_template( $template, $fallback_slug = '' ) {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template',
			'meta_value'     => $template,
			'fields'         => 'ids',
		)
	);
	if ( $pages ) {
		return get_permalink( $pages[0] );
	}
	return $fallback_slug ? jfz_page_url_by_slug( $fallback_slug ) : '';
}

function jfz_page_url_by_slug( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : '';
}

/**
 * Datos de un evento ya normalizados.
 */
function jfz_evento( $post = null ) {
	$post  = get_post( $post );
	$fecha = get_post_meta( $post->ID, '_jfz_evento_fecha', true );
	return array(
		'fecha'      => $fecha,
		'fecha_txt'  => $fecha ? jfz_fecha( $fecha ) : '',
		'hora'       => get_post_meta( $post->ID, '_jfz_evento_hora', true ),
		'lugar'      => get_post_meta( $post->ID, '_jfz_evento_lugar', true ),
		'ciudad'     => get_post_meta( $post->ID, '_jfz_evento_ciudad', true ),
		'link'       => get_post_meta( $post->ID, '_jfz_evento_link', true ),
		'link_texto' => get_post_meta( $post->ID, '_jfz_evento_link_texto', true ),
		'pasado'     => $fecha && $fecha < wp_date( 'Y-m-d' ),
	);
}

/**
 * Convierte el nombre del idioma en un código BCP 47 aproximado para el atributo lang.
 */
function jfz_lang_code( $nombre ) {
	$map = array(
		'ingl'   => 'en',
		'engl'   => 'en',
		'franc'  => 'fr',
		'portug' => 'pt',
		'ital'   => 'it',
		'alem'   => 'de',
		'catal'  => 'ca',
		'gallego' => 'gl',
		'lat'    => 'la',
		'griego' => 'el',
		'ruso'   => 'ru',
		'polaco' => 'pl',
		'chino'  => 'zh',
		'japon'  => 'ja',
	);
	$n = mb_strtolower( remove_accents( (string) $nombre ) );
	foreach ( $map as $prefix => $code ) {
		if ( str_starts_with( $n, $prefix ) ) {
			return $code;
		}
	}
	return '';
}
