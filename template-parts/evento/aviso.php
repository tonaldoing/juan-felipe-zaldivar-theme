<?php
/**
 * Aviso del próximo evento en portada. Desaparece solo cuando pasa la fecha.
 *
 * @package juan-felipe-zaldivar
 */

$proximos = jfz_get_proximos_eventos( 1 );
if ( ! $proximos ) {
	return;
}
$evento = $proximos[0];
$e      = jfz_evento( $evento );
?>
<aside class="aviso" aria-label="<?php echo esc_attr( jfz_option( 'aviso_evento' ) ); ?>">
	<p class="aviso__label"><?php echo esc_html( jfz_option( 'aviso_evento' ) ); ?></p>
	<p class="aviso__title"><a href="<?php echo esc_url( get_permalink( $evento ) ); ?>"><?php echo esc_html( get_the_title( $evento ) ); ?></a></p>
	<p class="aviso__meta">
		<?php echo esc_html( implode( ' · ', array_filter( array( $e['fecha_txt'], $e['hora'] ? $e['hora'] . ' h' : '', $e['lugar'], $e['ciudad'] ) ) ) ); ?>
	</p>
	<?php if ( $e['link'] ) : ?>
		<a class="button button--small" href="<?php echo esc_url( $e['link'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $e['link_texto'] ?: __( 'Más información', 'juan-felipe-zaldivar' ) ); ?></a>
	<?php endif; ?>
</aside>
