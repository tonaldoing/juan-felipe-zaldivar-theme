<?php
$partes = array();
if ( jfz_meta( 'autor_original' ) ) {
	$partes[] = '<span class="entry__autor">' . esc_html( jfz_meta( 'autor_original' ) ) . '</span>';
}
if ( jfz_meta( 'idioma' ) ) {
	$partes[] = esc_html( sprintf( __( 'traducción del %s', 'juan-felipe-zaldivar' ), jfz_meta( 'idioma' ) ) );
}
if ( $partes ) :
	?>
	<p class="entry__sub"><?php echo implode( ' · ', $partes ); // phpcs:ignore WordPress.Security.EscapeOutput -- escapado arriba. ?></p>
<?php endif; ?>
