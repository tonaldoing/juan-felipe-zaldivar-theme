<?php
/**
 * Contenido de ejemplo: aviso en el admin y botón para borrarlo de una vez.
 * Las entradas demo llevan la meta _jfz_demo = 1 (viene en demo-content/demo.xml).
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

function jfz_demo_ids() {
	return get_posts(
		array(
			'post_type'      => array( 'post', 'evento', 'page' ),
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => '_jfz_demo',
			'meta_value'     => '1',
		)
	);
}

add_action( 'admin_notices', 'jfz_demo_notice' );
function jfz_demo_notice() {
	if ( ! current_user_can( 'delete_others_posts' ) ) {
		return;
	}
	$ids = jfz_demo_ids();
	if ( ! $ids ) {
		return;
	}
	$url = wp_nonce_url( admin_url( 'admin-post.php?action=jfz_delete_demo' ), 'jfz_delete_demo' );
	?>
	<div class="notice notice-info">
		<p>
			<strong><?php esc_html_e( 'Hay contenido de ejemplo cargado.', 'juan-felipe-zaldivar' ); ?></strong>
			<?php
			printf(
				/* translators: %d cantidad de entradas */
				esc_html( _n( 'Es %d entrada de muestra para ver cómo se ve el sitio. Cuando cargues tus textos, borrala de una vez con el botón.', 'Son %d entradas de muestra para ver cómo se ve el sitio. Cuando cargues tus textos, borralas de una vez con el botón.', count( $ids ), 'juan-felipe-zaldivar' ) ),
				count( $ids )
			);
			?>
			<a class="button" style="margin-left:8px" href="<?php echo esc_url( $url ); ?>" onclick="return confirm('<?php echo esc_js( __( '¿Borrar todo el contenido de ejemplo? No se puede deshacer.', 'juan-felipe-zaldivar' ) ); ?>')"><?php esc_html_e( 'Borrar contenido de ejemplo', 'juan-felipe-zaldivar' ); ?></a>
		</p>
	</div>
	<?php
}

add_action( 'admin_post_jfz_delete_demo', 'jfz_delete_demo' );
function jfz_delete_demo() {
	if ( ! current_user_can( 'delete_others_posts' ) ) {
		wp_die( esc_html__( 'Sin permisos.', 'juan-felipe-zaldivar' ) );
	}
	check_admin_referer( 'jfz_delete_demo' );
	$count = 0;
	foreach ( jfz_demo_ids() as $id ) {
		if ( has_post_thumbnail( $id ) ) {
			wp_delete_attachment( get_post_thumbnail_id( $id ), true );
		}
		if ( wp_delete_post( $id, true ) ) {
			$count++;
		}
	}
	wp_safe_redirect( add_query_arg( 'jfz_demo_borrado', $count, admin_url( 'edit.php' ) ) );
	exit;
}

add_action( 'admin_notices', 'jfz_demo_deleted_notice' );
function jfz_demo_deleted_notice() {
	if ( empty( $_GET['jfz_demo_borrado'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	$n = absint( $_GET['jfz_demo_borrado'] ); // phpcs:ignore WordPress.Security.NonceVerification
	/* translators: %d cantidad */
	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( sprintf( __( 'Listo: se borraron %d entradas de ejemplo.', 'juan-felipe-zaldivar' ), $n ) ) . '</p></div>';
}

/**
 * Aviso para cargar el contenido de ejemplo cuando el sitio está vacío.
 * Importa demo-content/demo.xml sin necesidad del plugin importador.
 */
add_action( 'admin_notices', 'jfz_demo_offer_notice' );
function jfz_demo_offer_notice() {
	if ( ! current_user_can( 'manage_options' ) || get_option( 'jfz_demo_dismissed' ) || jfz_demo_ids() ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'dashboard' !== $screen->id && 'edit-post' !== $screen->id && 'themes' !== $screen->id ) {
		return;
	}
	$import  = wp_nonce_url( admin_url( 'admin-post.php?action=jfz_import_demo' ), 'jfz_import_demo' );
	$dismiss = wp_nonce_url( admin_url( 'admin-post.php?action=jfz_dismiss_demo' ), 'jfz_dismiss_demo' );
	?>
	<div class="notice notice-info">
		<p>
			<strong><?php esc_html_e( '¿Querés ver el sitio con contenido de ejemplo?', 'juan-felipe-zaldivar' ); ?></strong>
			<?php esc_html_e( 'Carga poemas, traducciones, notas y eventos de muestra para ver cómo se ve todo. Después se borran con un botón.', 'juan-felipe-zaldivar' ); ?>
			<a class="button button-primary" style="margin-left:8px" href="<?php echo esc_url( $import ); ?>"><?php esc_html_e( 'Cargar contenido de ejemplo', 'juan-felipe-zaldivar' ); ?></a>
			<a style="margin-left:8px" href="<?php echo esc_url( $dismiss ); ?>"><?php esc_html_e( 'No, gracias', 'juan-felipe-zaldivar' ); ?></a>
		</p>
	</div>
	<?php
}

add_action( 'admin_post_jfz_dismiss_demo', 'jfz_dismiss_demo' );
function jfz_dismiss_demo() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sin permisos.', 'juan-felipe-zaldivar' ) );
	}
	check_admin_referer( 'jfz_dismiss_demo' );
	update_option( 'jfz_demo_dismissed', 1 );
	wp_safe_redirect( admin_url() );
	exit;
}

add_action( 'admin_post_jfz_import_demo', 'jfz_import_demo_action' );
function jfz_import_demo_action() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sin permisos.', 'juan-felipe-zaldivar' ) );
	}
	check_admin_referer( 'jfz_import_demo' );
	$result = jfz_import_demo();
	if ( is_wp_error( $result ) ) {
		wp_die( esc_html( $result->get_error_message() ) );
	}
	wp_safe_redirect( add_query_arg( 'jfz_demo_cargado', $result, admin_url( 'edit.php' ) ) );
	exit;
}

/**
 * Importa el WXR del tema con wp_insert_post. Devuelve la cantidad de entradas creadas.
 */
function jfz_import_demo() {
	$file = JFZ_DIR . '/demo-content/demo.xml';
	if ( ! file_exists( $file ) ) {
		return new WP_Error( 'jfz_no_file', __( 'No se encontró demo-content/demo.xml en el tema.', 'juan-felipe-zaldivar' ) );
	}
	$prev = libxml_use_internal_errors( true );
	$xml  = simplexml_load_file( $file );
	libxml_use_internal_errors( $prev );
	if ( ! $xml ) {
		return new WP_Error( 'jfz_bad_xml', __( 'El archivo de ejemplo no se pudo leer.', 'juan-felipe-zaldivar' ) );
	}
	$ns    = $xml->getNamespaces( true );
	$count = 0;
	jfz_seed_tipos();

	foreach ( $xml->channel->item as $item ) {
		$wp      = $item->children( $ns['wp'] );
		$content = $item->children( $ns['content'] );
		$excerpt = $item->children( $ns['excerpt'] );
		$type    = (string) $wp->post_type;
		$slug    = (string) $wp->post_name;

		if ( ! in_array( $type, array( 'post', 'evento', 'page' ), true ) ) {
			continue;
		}
		if ( get_page_by_path( $slug, OBJECT, $type ) ) {
			continue; // Ya existe (por ejemplo, la página Índice): no se duplica.
		}

		$post_id = wp_insert_post(
			array(
				'post_type'     => $type,
				'post_title'    => (string) $item->title,
				'post_name'     => $slug,
				'post_content'  => (string) $content->encoded,
				'post_excerpt'  => (string) $excerpt->encoded,
				'post_status'   => 'publish',
				'post_date'     => (string) $wp->post_date,
				'post_date_gmt' => (string) $wp->post_date_gmt,
				'post_author'   => get_current_user_id(),
				'comment_status' => 'closed',
				'ping_status'   => 'closed',
			),
			true
		);
		if ( is_wp_error( $post_id ) ) {
			continue;
		}
		$count++;

		$tags = array();
		foreach ( $item->category as $cat ) {
			$domain = (string) $cat['domain'];
			if ( 'tipo' === $domain ) {
				wp_set_object_terms( $post_id, (string) $cat['nicename'], 'tipo', false );
			} elseif ( 'post_tag' === $domain ) {
				$tags[] = (string) $cat;
			}
		}
		if ( $tags ) {
			wp_set_object_terms( $post_id, $tags, 'post_tag', false );
		}
		foreach ( $wp->postmeta as $meta ) {
			$key = (string) $meta->meta_key;
			if ( str_starts_with( $key, '_jfz_' ) || '_wp_page_template' === $key ) {
				update_post_meta( $post_id, $key, (string) $meta->meta_value );
			}
		}
	}
	return $count;
}

add_action( 'admin_notices', 'jfz_demo_imported_notice' );
function jfz_demo_imported_notice() {
	if ( empty( $_GET['jfz_demo_cargado'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	$n = absint( $_GET['jfz_demo_cargado'] ); // phpcs:ignore WordPress.Security.NonceVerification
	/* translators: %d cantidad */
	echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( sprintf( __( 'Listo: se cargaron %d entradas de ejemplo.', 'juan-felipe-zaldivar' ), $n ) ) . ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Ver el sitio', 'juan-felipe-zaldivar' ) . '</a></p></div>';
}
