<?php
/**
 * Una entrada en el listado.
 *
 * @package juan-felipe-zaldivar
 */

$tipo = jfz_get_tipo();
$slug = $tipo ? $tipo->slug : 'nota';
?>
<article <?php post_class( 'card card--' . $slug ); ?>>
	<p class="card__meta">
		<?php if ( $tipo ) : ?>
			<a class="tipo-label" href="<?php echo esc_url( get_term_link( $tipo ) ); ?>"><?php echo esc_html( $tipo->name ); ?></a>
		<?php endif; ?>
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( jfz_fecha() ); ?></time>
	</p>
	<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php if ( 'traduccion' === $slug && jfz_meta( 'autor_original' ) ) : ?>
		<p class="card__sub"><?php echo esc_html( jfz_meta( 'autor_original' ) ); ?></p>
	<?php elseif ( in_array( $slug, array( 'libros', 'cine' ), true ) && jfz_meta( 'obra_titulo' ) ) : ?>
		<p class="card__sub"><?php esc_html_e( 'Sobre', 'juan-felipe-zaldivar' ); ?> <em><?php echo esc_html( jfz_meta( 'obra_titulo' ) ); ?></em><?php echo jfz_meta( 'obra_autor' ) ? ', ' . esc_html( jfz_meta( 'obra_autor' ) ) : ( jfz_meta( 'obra_director' ) ? ', ' . esc_html( jfz_meta( 'obra_director' ) ) : '' ); ?></p>
	<?php endif; ?>
	<div class="card__preview">
		<?php echo jfz_entry_preview(); // phpcs:ignore WordPress.Security.EscapeOutput -- escapado dentro de la función. ?>
	</div>
	<a class="card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Leer', 'juan-felipe-zaldivar' ); ?> <span aria-hidden="true">→</span><span class="screen-reader-text">: <?php the_title(); ?></span></a>
</article>
