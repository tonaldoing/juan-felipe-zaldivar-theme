<?php
/**
 * Página estática (Sobre mí, etc.).
 *
 * @package juan-felipe-zaldivar
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'entry entry--page' ); ?>>
		<header class="entry__header">
			<h1 class="entry__title"><?php the_title(); ?></h1>
		</header>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="entry__thumb entry__thumb--page"><?php the_post_thumbnail( 'medium_large' ); ?></figure>
		<?php endif; ?>
		<div class="entry__content">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
