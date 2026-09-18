<?php
/**
 * Ajustes de consultas: portada, archivos de tipo, eventos.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

add_action( 'pre_get_posts', 'jfz_pre_get_posts' );
function jfz_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		if ( is_admin() && $query->is_main_query() && 'evento' === $query->get( 'post_type' ) && 'jfz_fecha' === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', '_jfz_evento_fecha' );
			$query->set( 'orderby', 'meta_value' );
		}
		return;
	}

	// Archivo de eventos: todos, ordenados por fecha del evento (el template separa próximos y pasados).
	if ( $query->is_post_type_archive( 'evento' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'meta_key', '_jfz_evento_fecha' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'DESC' );
	}

	// La búsqueda incluye entradas, eventos y páginas.
	if ( $query->is_search() ) {
		$query->set( 'post_type', array( 'post', 'evento', 'page' ) );
	}
}

/**
 * Próximos eventos (fecha de hoy en adelante), del más cercano al más lejano.
 */
function jfz_get_proximos_eventos( $limit = -1 ) {
	return get_posts(
		array(
			'post_type'      => 'evento',
			'posts_per_page' => $limit,
			'meta_key'       => '_jfz_evento_fecha',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => '_jfz_evento_fecha',
					'value'   => wp_date( 'Y-m-d' ),
					'compare' => '>=',
					'type'    => 'DATE',
				),
			),
		)
	);
}

/**
 * Entrada anterior y siguiente del mismo tipo, para la navegación al pie.
 */
function jfz_adjacent_same_tipo( $previous = true ) {
	return get_adjacent_post( true, '', $previous, 'tipo' );
}
