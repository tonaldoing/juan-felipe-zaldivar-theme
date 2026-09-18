<?php
/**
 * Template Name: Índice
 * Description: Lista automática de todos los textos agrupados por tipo. El contenido de la página, si hay, va arriba como introducción.
 *
 * @package juan-felipe-zaldivar
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'entry entry--indice' ); ?>>
		<header class="entry__header">
			<h1 class="entry__title"><?php the_title(); ?></h1>
		</header>
		<?php if ( trim( get_the_content() ) ) : ?>
			<div class="entry__content entry__content--intro"><?php the_content(); ?></div>
		<?php endif; ?>

		<?php
		$secciones = array();
		foreach ( jfz_tipos() as $slug => $label ) {
			$posts = get_posts(
				array(
					'post_type'      => 'post',
					'posts_per_page' => -1,
					'orderby'        => 'date',
					'order'          => 'DESC',
					'tax_query'      => array( array( 'taxonomy' => 'tipo', 'field' => 'slug', 'terms' => $slug ) ),
				)
			);
			if ( $posts ) {
				$secciones[ $slug ] = $posts;
			}
		}
		?>

		<?php if ( $secciones ) : ?>
			<nav class="indice-nav" aria-label="<?php esc_attr_e( 'Secciones del índice', 'juan-felipe-zaldivar' ); ?>">
				<?php foreach ( $secciones as $slug => $posts ) : ?>
					<a href="#indice-<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( jfz_tipo_plural( $slug ) ); ?> <span class="indice-nav__count"><?php echo count( $posts ); ?></span></a>
				<?php endforeach; ?>
			</nav>

			<?php foreach ( $secciones as $slug => $posts ) : ?>
				<section class="indice" id="indice-<?php echo esc_attr( $slug ); ?>">
					<h2 class="section-title"><?php echo esc_html( jfz_tipo_plural( $slug ) ); ?></h2>
					<ol class="indice__list" reversed>
						<?php foreach ( $posts as $p ) : ?>
							<li class="indice__item">
								<a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><?php echo esc_html( get_the_title( $p ) ); ?></a>
								<?php if ( 'traduccion' === $slug && get_post_meta( $p->ID, '_jfz_autor_original', true ) ) : ?>
									<span class="indice__extra"><?php echo esc_html( get_post_meta( $p->ID, '_jfz_autor_original', true ) ); ?></span>
								<?php elseif ( in_array( $slug, array( 'libros', 'cine' ), true ) && get_post_meta( $p->ID, '_jfz_obra_titulo', true ) ) : ?>
									<span class="indice__extra"><em><?php echo esc_html( get_post_meta( $p->ID, '_jfz_obra_titulo', true ) ); ?></em></span>
								<?php endif; ?>
								<span class="indice__year"><?php echo esc_html( get_the_date( 'Y', $p ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ol>
				</section>
			<?php endforeach; ?>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Todavía no hay textos para indexar.', 'juan-felipe-zaldivar' ); ?></p>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
