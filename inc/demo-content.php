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
