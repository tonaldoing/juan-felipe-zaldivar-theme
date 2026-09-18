<?php
/**
 * Campos extra de entradas (según tipo) y de eventos.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Definición de campos de entrada, agrupados por el tipo que los usa.
 * Los campos de "obra" se comparten entre Libros y Cine.
 */
function jfz_entry_fields() {
	return array(
		'poema'      => array(
			'libro' => array( 'label' => __( 'Del libro (opcional)', 'juan-felipe-zaldivar' ), 'type' => 'text', 'placeholder' => 'Título del libro al que pertenece' ),
		),
		'traduccion' => array(
			'autor_original'  => array( 'label' => __( 'Autor original', 'juan-felipe-zaldivar' ), 'type' => 'text', 'placeholder' => 'Emily Dickinson' ),
			'titulo_original' => array( 'label' => __( 'Título original', 'juan-felipe-zaldivar' ), 'type' => 'text' ),
			'idioma'          => array( 'label' => __( 'Idioma original', 'juan-felipe-zaldivar' ), 'type' => 'text', 'placeholder' => 'inglés' ),
			'texto_original'  => array( 'label' => __( 'Texto original (se muestra junto a la traducción)', 'juan-felipe-zaldivar' ), 'type' => 'textarea' ),
		),
		'libros'     => array(
			'obra_titulo'    => array( 'label' => __( 'Título del libro', 'juan-felipe-zaldivar' ), 'type' => 'text' ),
			'obra_autor'     => array( 'label' => __( 'Autor', 'juan-felipe-zaldivar' ), 'type' => 'text' ),
			'obra_anio'      => array( 'label' => __( 'Año', 'juan-felipe-zaldivar' ), 'type' => 'text', 'placeholder' => '1955' ),
			'obra_editorial' => array( 'label' => __( 'Editorial (opcional)', 'juan-felipe-zaldivar' ), 'type' => 'text' ),
		),
		'cine'       => array(
			'obra_titulo'   => array( 'label' => __( 'Título de la película', 'juan-felipe-zaldivar' ), 'type' => 'text' ),
			'obra_director' => array( 'label' => __( 'Dirección', 'juan-felipe-zaldivar' ), 'type' => 'text' ),
			'obra_anio'     => array( 'label' => __( 'Año', 'juan-felipe-zaldivar' ), 'type' => 'text', 'placeholder' => '1979' ),
		),
	);
}

function jfz_evento_fields() {
	return array(
		'evento_fecha'      => array( 'label' => __( 'Fecha', 'juan-felipe-zaldivar' ), 'type' => 'date' ),
		'evento_hora'       => array( 'label' => __( 'Hora', 'juan-felipe-zaldivar' ), 'type' => 'time' ),
		'evento_lugar'      => array( 'label' => __( 'Lugar', 'juan-felipe-zaldivar' ), 'type' => 'text', 'placeholder' => 'Librería, bar, centro cultural' ),
		'evento_ciudad'     => array( 'label' => __( 'Ciudad', 'juan-felipe-zaldivar' ), 'type' => 'text' ),
		'evento_link'       => array( 'label' => __( 'Link (entradas, mapa, más info)', 'juan-felipe-zaldivar' ), 'type' => 'url', 'placeholder' => 'https://' ),
		'evento_link_texto' => array( 'label' => __( 'Texto del botón', 'juan-felipe-zaldivar' ), 'type' => 'text', 'placeholder' => 'Más información' ),
	);
}

add_action( 'add_meta_boxes', 'jfz_add_meta_boxes' );
function jfz_add_meta_boxes() {
	add_meta_box( 'jfz_tipo', __( 'Tipo de texto', 'juan-felipe-zaldivar' ), 'jfz_tipo_meta_box', 'post', 'normal', 'high' );
	add_meta_box( 'jfz_entry_meta', __( 'Detalles según el tipo de texto', 'juan-felipe-zaldivar' ), 'jfz_render_entry_meta', 'post', 'normal', 'high' );
	add_meta_box( 'jfz_evento_meta', __( 'Datos del evento', 'juan-felipe-zaldivar' ), 'jfz_render_evento_meta', 'evento', 'normal', 'high' );
}

function jfz_render_field( $key, $field, $post_id ) {
	$value = get_post_meta( $post_id, '_jfz_' . $key, true );
	$id    = 'jfz_' . $key;
	printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $field['label'] ) );
	if ( 'textarea' === $field['type'] ) {
		printf( '<textarea id="%1$s" name="%1$s" rows="10">%2$s</textarea>', esc_attr( $id ), esc_textarea( $value ) );
	} else {
		printf( '<input type="%s" id="%s" name="%s" value="%s" placeholder="%s">', esc_attr( $field['type'] ), esc_attr( $id ), esc_attr( $id ), esc_attr( $value ), esc_attr( $field['placeholder'] ?? '' ) );
	}
}

function jfz_render_entry_meta( $post ) {
	wp_nonce_field( 'jfz_entry_meta_save', 'jfz_entry_meta_nonce' );
	$current = jfz_get_tipo_slug( $post );
	$tipos   = jfz_tipos();
	echo '<p class="description">' . esc_html__( 'Acá se muestran solo los campos del tipo de texto elegido arriba.', 'juan-felipe-zaldivar' ) . '</p>';
	foreach ( jfz_entry_fields() as $tipo => $fields ) {
		printf( '<div class="jfz-meta-group" data-tipo="%s" %s><h4>%s</h4>', esc_attr( $tipo ), $current === $tipo ? '' : 'hidden', esc_html( $tipos[ $tipo ] ) );
		foreach ( $fields as $key => $field ) {
			// El mismo campo puede existir en dos grupos (obra_titulo). Se sufija el name con el tipo.
			jfz_render_field_grouped( $key, $field, $post->ID, $tipo );
		}
		echo '</div>';
	}
	echo '<div class="jfz-meta-group" data-tipo="nota" ' . ( 'nota' === $current ? '' : 'hidden' ) . '><p class="description">' . esc_html__( 'Las notas no tienen campos extra.', 'juan-felipe-zaldivar' ) . '</p></div>';
}

function jfz_render_field_grouped( $key, $field, $post_id, $tipo ) {
	$value = get_post_meta( $post_id, '_jfz_' . $key, true );
	$name  = 'jfz_meta[' . $tipo . '][' . $key . ']';
	$id    = 'jfz_' . $tipo . '_' . $key;
	printf( '<label for="%s">%s</label>', esc_attr( $id ), esc_html( $field['label'] ) );
	if ( 'textarea' === $field['type'] ) {
		printf( '<textarea id="%s" name="%s" rows="12" spellcheck="false">%s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( $value ) );
	} else {
		printf( '<input type="%s" id="%s" name="%s" value="%s" placeholder="%s">', esc_attr( $field['type'] ), esc_attr( $id ), esc_attr( $name ), esc_attr( $value ), esc_attr( $field['placeholder'] ?? '' ) );
	}
}

function jfz_render_evento_meta( $post ) {
	wp_nonce_field( 'jfz_evento_meta_save', 'jfz_evento_meta_nonce' );
	echo '<div class="jfz-meta-group">';
	foreach ( jfz_evento_fields() as $key => $field ) {
		jfz_render_field( $key, $field, $post->ID );
	}
	echo '</div>';
	echo '<p class="description">' . esc_html__( 'El evento aparece destacado en la portada hasta el día de la fecha; después pasa solo a "Eventos pasados".', 'juan-felipe-zaldivar' ) . '</p>';
}

/**
 * Guardado de campos de entrada. Corre después de jfz_save_tipo (prioridad 20)
 * para saber qué grupo de campos corresponde.
 */
add_action( 'save_post_post', 'jfz_save_entry_meta', 20, 2 );
function jfz_save_entry_meta( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['jfz_entry_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['jfz_entry_meta_nonce'] ), 'jfz_entry_meta_save' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$tipo   = jfz_get_tipo_slug( $post_id );
	$fields = jfz_entry_fields();
	$raw    = isset( $_POST['jfz_meta'] ) && is_array( $_POST['jfz_meta'] ) ? wp_unslash( $_POST['jfz_meta'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- se sanitiza campo por campo abajo.

	// Se borran todos los campos de tipos que no son el actual, para no arrastrar datos viejos.
	foreach ( $fields as $group => $group_fields ) {
		foreach ( $group_fields as $key => $field ) {
			if ( $group !== $tipo ) {
				continue;
			}
			$value = $raw[ $group ][ $key ] ?? '';
			$value = 'textarea' === $field['type'] ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
			if ( '' === $value ) {
				delete_post_meta( $post_id, '_jfz_' . $key );
			} else {
				update_post_meta( $post_id, '_jfz_' . $key, $value );
			}
		}
	}
	foreach ( $fields as $group => $group_fields ) {
		if ( $group === $tipo ) {
			continue;
		}
		foreach ( $group_fields as $key => $field ) {
			if ( ! isset( $fields[ $tipo ][ $key ] ) ) {
				delete_post_meta( $post_id, '_jfz_' . $key );
			}
		}
	}
}

add_action( 'save_post_evento', 'jfz_save_evento_meta', 10, 2 );
function jfz_save_evento_meta( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['jfz_evento_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['jfz_evento_meta_nonce'] ), 'jfz_evento_meta_save' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( jfz_evento_fields() as $key => $field ) {
		$value = isset( $_POST[ 'jfz_' . $key ] ) ? wp_unslash( $_POST[ 'jfz_' . $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		switch ( $field['type'] ) {
			case 'url':
				$value = esc_url_raw( $value );
				break;
			case 'date':
				$value = preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ? $value : '';
				break;
			case 'time':
				$value = preg_match( '/^\d{2}:\d{2}$/', $value ) ? $value : '';
				break;
			default:
				$value = sanitize_text_field( $value );
		}
		if ( '' === $value ) {
			delete_post_meta( $post_id, '_jfz_' . $key );
		} else {
			update_post_meta( $post_id, '_jfz_' . $key, $value );
		}
	}
}

/**
 * Meta expuesta en REST (solo lectura pública, escritura con permisos) para futuros usos.
 */
add_action( 'init', 'jfz_register_meta' );
function jfz_register_meta() {
	$all = array();
	foreach ( jfz_entry_fields() as $group ) {
		$all += $group;
	}
	foreach ( $all as $key => $field ) {
		register_post_meta( 'post', '_jfz_' . $key, array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_textarea_field', 'auth_callback' => fn() => current_user_can( 'edit_posts' ) ) );
	}
	foreach ( jfz_evento_fields() as $key => $field ) {
		register_post_meta( 'evento', '_jfz_' . $key, array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field', 'auth_callback' => fn() => current_user_can( 'edit_posts' ) ) );
	}
}
