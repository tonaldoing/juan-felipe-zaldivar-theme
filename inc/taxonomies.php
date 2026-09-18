<?php
/**
 * Taxonomía "tipo": una sola por entrada, obligatoria.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'jfz_register_tipo' );
function jfz_register_tipo() {
	register_taxonomy(
		'tipo',
		array( 'post' ),
		array(
			'labels'            => array(
				'name'          => __( 'Tipos de texto', 'juan-felipe-zaldivar' ),
				'singular_name' => __( 'Tipo de texto', 'juan-felipe-zaldivar' ),
				'menu_name'     => __( 'Tipos de texto', 'juan-felipe-zaldivar' ),
				'all_items'     => __( 'Todos los tipos', 'juan-felipe-zaldivar' ),
				'edit_item'     => __( 'Editar tipo', 'juan-felipe-zaldivar' ),
				'not_found'     => __( 'No hay tipos', 'juan-felipe-zaldivar' ),
			),
			'public'            => true,
			'hierarchical'      => false,
			// Sin REST a propósito: así el editor de bloques usa el meta box de radios de abajo
			// en vez de su panel de casillas múltiples.
			'show_in_rest'      => false,
			'show_admin_column' => true,
			'show_in_quick_edit' => false,
			// El meta box se registra a mano en inc/meta.php: el automático de WP no se muestra en el editor de bloques.
			'meta_box_cb'       => false,
			'rewrite'           => array( 'slug' => 'tipo', 'with_front' => false ),
			'capabilities'      => array(
				// Solo administradores crean/borrán tipos; el autor solo elige entre los existentes.
				'manage_terms' => 'manage_options',
				'edit_terms'   => 'manage_options',
				'delete_terms' => 'manage_options',
				'assign_terms' => 'edit_posts',
			),
		)
	);
}

/**
 * Crea los tipos base al activar el tema (idempotente).
 */
add_action( 'after_switch_theme', 'jfz_seed_tipos' );
add_action( 'init', 'jfz_seed_tipos_once', 20 );
function jfz_seed_tipos_once() {
	if ( ! get_option( 'jfz_tipos_seeded' ) ) {
		jfz_seed_tipos();
	}
}
function jfz_seed_tipos() {
	if ( ! taxonomy_exists( 'tipo' ) ) {
		jfz_register_tipo();
	}
	$descripciones = array(
		'poema'      => 'Poemas propios.',
		'traduccion' => 'Traducciones de poemas de otros autores.',
		'libros'     => 'Lecturas y notas sobre libros.',
		'cine'       => 'Notas sobre películas.',
		'nota'       => 'Textos varios.',
	);
	foreach ( jfz_tipos() as $slug => $label ) {
		if ( ! term_exists( $slug, 'tipo' ) ) {
			wp_insert_term( $label, 'tipo', array( 'slug' => $slug, 'description' => $descripciones[ $slug ] ) );
		}
	}
	update_option( 'jfz_tipos_seeded', 1 );
}

/**
 * Meta box con radios: se elige un solo tipo. Se registra en jfz_add_meta_boxes().
 */
function jfz_tipo_meta_box( $post ) {
	$terms   = get_terms( array( 'taxonomy' => 'tipo', 'hide_empty' => false ) );
	$current = jfz_get_tipo_slug( $post );
	$order   = array_keys( jfz_tipos() );
	usort( $terms, fn( $a, $b ) => ( array_search( $a->slug, $order, true ) ?: 99 ) <=> ( array_search( $b->slug, $order, true ) ?: 99 ) );
	wp_nonce_field( 'jfz_tipo_save', 'jfz_tipo_nonce' );
	echo '<div class="jfz-tipo-radio">';
	foreach ( $terms as $term ) {
		printf(
			'<label><input type="radio" name="jfz_tipo" value="%s" %s> %s</label>',
			esc_attr( $term->slug ),
			checked( $current, $term->slug, false ),
			esc_html( $term->name )
		);
	}
	echo '</div>';
	echo '<p class="description">' . esc_html__( 'Cada entrada tiene un solo tipo. Cambia cómo se muestra el texto y qué campos extra aparecen abajo.', 'juan-felipe-zaldivar' ) . '</p>';
}

add_action( 'save_post_post', 'jfz_save_tipo', 10, 2 );
function jfz_save_tipo( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	if ( isset( $_POST['jfz_tipo_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['jfz_tipo_nonce'] ), 'jfz_tipo_save' ) && current_user_can( 'edit_post', $post_id ) ) {
		$slug = isset( $_POST['jfz_tipo'] ) ? sanitize_key( $_POST['jfz_tipo'] ) : '';
		if ( $slug && term_exists( $slug, 'tipo' ) ) {
			wp_set_object_terms( $post_id, $slug, 'tipo', false );
			return;
		}
	}
	// Sin tipo elegido (editor de bloques, importación, REST): "nota" por defecto.
	if ( 'auto-draft' !== $post->post_status && ! jfz_get_tipo( $post_id ) ) {
		wp_set_object_terms( $post_id, 'nota', 'tipo', false );
	}
}

/**
 * Las categorías nativas no se usan: se ocultan del admin para no confundir.
 */
add_action( 'admin_menu', 'jfz_hide_categories' );
function jfz_hide_categories() {
	remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' );
}
add_action( 'init', 'jfz_unregister_category_ui', 30 );
function jfz_unregister_category_ui() {
	global $wp_taxonomies;
	if ( isset( $wp_taxonomies['category'] ) ) {
		$wp_taxonomies['category']->show_ui         = false;
		$wp_taxonomies['category']->show_in_rest    = false;
		$wp_taxonomies['category']->show_admin_column = false;
	}
}
