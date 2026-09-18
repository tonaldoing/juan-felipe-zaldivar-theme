<?php
/**
 * Un evento.
 *
 * @package juan-felipe-zaldivar
 */

get_header();

while ( have_posts() ) :
	the_post();
	$e = jfz_evento();
	?>
	<article <?php post_class( 'entry entry--evento' . ( $e['pasado'] ? ' entry--evento-pasado' : '' ) ); ?>>
		<header class="entry__header">
			<p class="entry__meta">
				<a class="tipo-label" href="<?php echo esc_url( get_post_type_archive_link( 'evento' ) ); ?>"><?php echo $e['pasado'] ? esc_html__( 'Evento pasado', 'juan-felipe-zaldivar' ) : esc_html__( 'Evento', 'juan-felipe-zaldivar' ); ?></a>
			</p>
			<h1 class="entry__title"><?php the_title(); ?></h1>
			<dl class="evento-datos">
				<?php if ( $e['fecha'] ) : ?>
					<dt><?php esc_html_e( 'Cuándo', 'juan-felipe-zaldivar' ); ?></dt>
					<dd><time datetime="<?php echo esc_attr( $e['fecha'] . ( $e['hora'] ? 'T' . $e['hora'] : '' ) ); ?>"><?php echo esc_html( $e['fecha_txt'] . ( $e['hora'] ? ', ' . $e['hora'] . ' h' : '' ) ); ?></time></dd>
				<?php endif; ?>
				<?php if ( $e['lugar'] || $e['ciudad'] ) : ?>
					<dt><?php esc_html_e( 'Dónde', 'juan-felipe-zaldivar' ); ?></dt>
					<dd><?php echo esc_html( implode( ', ', array_filter( array( $e['lugar'], $e['ciudad'] ) ) ) ); ?></dd>
				<?php endif; ?>
			</dl>
			<?php if ( $e['link'] ) : ?>
				<p><a class="button" href="<?php echo esc_url( $e['link'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $e['link_texto'] ?: __( 'Más información', 'juan-felipe-zaldivar' ) ); ?></a></p>
			<?php endif; ?>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="entry__thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
		<?php endif; ?>

		<div class="entry__content">
			<?php the_content(); ?>
		</div>

		<footer class="entry__footer">
			<p class="entry__back"><a href="<?php echo esc_url( get_post_type_archive_link( 'evento' ) ); ?>">← <?php esc_html_e( 'Todos los eventos', 'juan-felipe-zaldivar' ); ?></a></p>
		</footer>
	</article>
	<?php
endwhile;

get_footer();
