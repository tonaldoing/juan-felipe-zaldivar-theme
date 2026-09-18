<?php
/**
 * Eventos: próximos primero, después los pasados.
 *
 * @package juan-felipe-zaldivar
 */

get_header();

$proximos = array();
$pasados  = array();
while ( have_posts() ) {
	the_post();
	$e = jfz_evento();
	if ( $e['pasado'] || ! $e['fecha'] ) {
		$pasados[] = get_post();
	} else {
		$proximos[] = get_post();
	}
}
// La consulta viene en orden descendente; los próximos se muestran del más cercano al más lejano.
$proximos = array_reverse( $proximos );
?>
<header class="page-header">
	<h1 class="page-header__title"><?php esc_html_e( 'Eventos', 'juan-felipe-zaldivar' ); ?></h1>
	<p class="page-header__desc"><?php esc_html_e( 'Lecturas, presentaciones y charlas.', 'juan-felipe-zaldivar' ); ?></p>
</header>

<section class="eventos" aria-labelledby="proximos">
	<h2 id="proximos" class="section-title"><?php esc_html_e( 'Próximos', 'juan-felipe-zaldivar' ); ?></h2>
	<?php if ( $proximos ) : ?>
		<?php
		foreach ( $proximos as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			setup_postdata( $post );
			get_template_part( 'template-parts/evento/card' );
		endforeach;
		wp_reset_postdata();
		?>
	<?php else : ?>
		<p class="empty"><?php esc_html_e( 'Por ahora no hay eventos programados. Si querés enterarte del próximo, suscribite al correo.', 'juan-felipe-zaldivar' ); ?></p>
	<?php endif; ?>
</section>

<?php if ( $pasados ) : ?>
	<section class="eventos eventos--pasados" aria-labelledby="pasados">
		<h2 id="pasados" class="section-title"><?php esc_html_e( 'Pasados', 'juan-felipe-zaldivar' ); ?></h2>
		<?php
		foreach ( $pasados as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			setup_postdata( $post );
			get_template_part( 'template-parts/evento/card' );
		endforeach;
		wp_reset_postdata();
		?>
	</section>
<?php endif; ?>

<?php get_footer(); ?>
