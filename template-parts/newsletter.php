<?php
/**
 * Caja de suscripción. No se muestra si no hay URL configurada.
 *
 * @package juan-felipe-zaldivar
 */

$url = jfz_option( 'newsletter_url' );
if ( ! $url ) {
	return;
}
$campo  = jfz_option( 'newsletter_campo' ) ?: 'email';
$metodo = 'get' === jfz_option( 'newsletter_metodo' ) ? 'get' : 'post';
?>
<section class="newsletter" aria-labelledby="newsletter-titulo">
	<h2 id="newsletter-titulo" class="newsletter__title"><?php echo esc_html( jfz_option( 'newsletter_titulo' ) ); ?></h2>
	<?php if ( jfz_option( 'newsletter_texto' ) ) : ?>
		<p class="newsletter__text"><?php echo esc_html( jfz_option( 'newsletter_texto' ) ); ?></p>
	<?php endif; ?>
	<form class="newsletter__form" action="<?php echo esc_url( $url ); ?>" method="<?php echo esc_attr( $metodo ); ?>" target="_blank" rel="noopener">
		<label class="screen-reader-text" for="newsletter-email"><?php esc_html_e( 'Tu correo', 'juan-felipe-zaldivar' ); ?></label>
		<input class="newsletter__input" type="email" id="newsletter-email" name="<?php echo esc_attr( $campo ); ?>" required placeholder="<?php esc_attr_e( 'tu@correo.com', 'juan-felipe-zaldivar' ); ?>" autocomplete="email">
		<button class="button" type="submit"><?php esc_html_e( 'Suscribirme', 'juan-felipe-zaldivar' ); ?></button>
	</form>
</section>
