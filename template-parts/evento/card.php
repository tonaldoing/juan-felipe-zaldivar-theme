<?php
/**
 * Un evento en el listado.
 *
 * @package juan-felipe-zaldivar
 */

$e = jfz_evento();
?>
<article <?php post_class( 'evento-card' . ( $e['pasado'] ? ' evento-card--pasado' : '' ) ); ?>>
	<div class="evento-card__fecha">
		<?php if ( $e['fecha'] ) : ?>
			<time datetime="<?php echo esc_attr( $e['fecha'] ); ?>">
				<span class="evento-card__dia"><?php echo esc_html( (int) substr( $e['fecha'], 8, 2 ) ); ?></span>
				<span class="evento-card__mes"><?php echo esc_html( jfz_mes( (int) substr( $e['fecha'], 5, 2 ), true ) . ' ' . substr( $e['fecha'], 0, 4 ) ); ?></span>
			</time>
		<?php endif; ?>
	</div>
	<div class="evento-card__body">
		<h2 class="evento-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="evento-card__lugar">
			<?php echo esc_html( implode( ' · ', array_filter( array( $e['hora'] ? $e['hora'] . ' h' : '', $e['lugar'], $e['ciudad'] ) ) ) ); ?>
		</p>
		<?php if ( has_excerpt() ) : ?>
			<p class="evento-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>
		<?php if ( $e['link'] && ! $e['pasado'] ) : ?>
			<a class="button button--small" href="<?php echo esc_url( $e['link'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $e['link_texto'] ?: __( 'Más información', 'juan-felipe-zaldivar' ) ); ?></a>
		<?php endif; ?>
	</div>
</article>
