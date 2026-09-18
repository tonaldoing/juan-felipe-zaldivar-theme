<?php
/**
 * Resultados de búsqueda.
 *
 * @package juan-felipe-zaldivar
 */

get_header();
?>
<header class="page-header">
	<h1 class="page-header__title"><?php esc_html_e( 'Búsqueda', 'juan-felipe-zaldivar' ); ?></h1>
	<p class="page-header__desc">
		<?php
		printf(
			/* translators: 1: cantidad, 2: término buscado */
			esc_html( _n( '%1$d resultado para “%2$s”', '%1$d resultados para “%2$s”', (int) $wp_query->found_posts, 'juan-felipe-zaldivar' ) ),
			(int) $wp_query->found_posts,
			esc_html( get_search_query() )
		);
		?>
	</p>
	<?php get_search_form(); ?>
</header>

<?php if ( have_posts() ) : ?>
	<div class="flow">
		<?php
		while ( have_posts() ) :
			the_post();
			if ( 'evento' === get_post_type() ) {
				get_template_part( 'template-parts/evento/card' );
			} elseif ( 'page' === get_post_type() ) {
				?>
				<article <?php post_class( 'card card--page' ); ?>>
					<p class="card__meta"><span class="tipo-label"><?php esc_html_e( 'Página', 'juan-felipe-zaldivar' ); ?></span></p>
					<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</article>
				<?php
			} else {
				get_template_part( 'template-parts/entry/card' );
			}
		endwhile;
		?>
	</div>
	<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '←', 'next_text' => '→' ) ); ?>
<?php else : ?>
	<p class="empty"><?php esc_html_e( 'No encontré nada con esas palabras. Probá con otras, o mirá el índice completo.', 'juan-felipe-zaldivar' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
