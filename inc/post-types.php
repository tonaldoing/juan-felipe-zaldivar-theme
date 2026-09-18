<?php
/**
 * Tipo de contenido "evento".
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'jfz_register_evento' );
function jfz_register_evento() {
	register_post_type(
		'evento',
		array(
			'labels'       => array(
				'name'               => __( 'Eventos', 'juan-felipe-zaldivar' ),
				'singular_name'      => __( 'Evento', 'juan-felipe-zaldivar' ),
				'add_new'            => __( 'Agregar evento', 'juan-felipe-zaldivar' ),
				'add_new_item'       => __( 'Agregar evento', 'juan-felipe-zaldivar' ),
				'edit_item'          => __( 'Editar evento', 'juan-felipe-zaldivar' ),
				'new_item'           => __( 'Nuevo evento', 'juan-felipe-zaldivar' ),
				'view_item'          => __( 'Ver evento', 'juan-felipe-zaldivar' ),
				'search_items'       => __( 'Buscar eventos', 'juan-felipe-zaldivar' ),
				'not_found'          => __( 'No hay eventos', 'juan-felipe-zaldivar' ),
				'not_found_in_trash' => __( 'No hay eventos en la papelera', 'juan-felipe-zaldivar' ),
				'all_items'          => __( 'Todos los eventos', 'juan-felipe-zaldivar' ),
			),
			'public'       => true,
			'has_archive'  => 'eventos',
			'rewrite'      => array( 'slug' => 'evento', 'with_front' => false ),
			'menu_icon'    => 'dashicons-calendar-alt',
			'menu_position' => 6,
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		)
	);
}

/**
 * Columna con la fecha del evento en el listado del admin.
 */
add_filter( 'manage_evento_posts_columns', 'jfz_evento_columns' );
function jfz_evento_columns( $cols ) {
	$new = array();
	foreach ( $cols as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['jfz_fecha'] = __( 'Fecha del evento', 'juan-felipe-zaldivar' );
			$new['jfz_lugar'] = __( 'Lugar', 'juan-felipe-zaldivar' );
		}
	}
	unset( $new['date'] );
	return $new;
}
add_action( 'manage_evento_posts_custom_column', 'jfz_evento_column', 10, 2 );
function jfz_evento_column( $col, $post_id ) {
	$e = jfz_evento( $post_id );
	if ( 'jfz_fecha' === $col ) {
		echo $e['fecha'] ? esc_html( $e['fecha_txt'] . ( $e['hora'] ? ', ' . $e['hora'] : '' ) ) : '<span style="color:#b32d2e">' . esc_html__( 'Sin fecha', 'juan-felipe-zaldivar' ) . '</span>';
		if ( $e['pasado'] ) {
			echo ' <em>(' . esc_html__( 'pasado', 'juan-felipe-zaldivar' ) . ')</em>';
		}
	}
	if ( 'jfz_lugar' === $col ) {
		echo esc_html( trim( $e['lugar'] . ( $e['ciudad'] ? ', ' . $e['ciudad'] : '' ) ) );
	}
}
add_filter( 'manage_edit-evento_sortable_columns', fn( $c ) => $c + array( 'jfz_fecha' => 'jfz_fecha' ) );
