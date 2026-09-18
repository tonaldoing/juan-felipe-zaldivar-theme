<?php
/**
 * Una entrada. El layout cambia según el tipo de texto.
 *
 * @package juan-felipe-zaldivar
 */

get_header();

while ( have_posts() ) :
	the_post();
	$tipo = jfz_get_tipo();
	$slug = $tipo ? $tipo->slug : 'nota';
	?>
	<article <?php post_class( 'entry entry--' . $slug ); ?>>
		<header class="entry__header">
			<p class="entry__meta">
				<?php if ( $tipo ) : ?>
					<a class="tipo-label" href="<?php echo esc_url( get_term_link( $tipo ) ); ?>"><?php echo esc_html( $tipo->name ); ?></a>
				<?php endif; ?>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( jfz_fecha() ); ?></time>
			</p>
			<h1 class="entry__title"><?php the_title(); ?></h1>
			<?php get_template_part( 'template-parts/entry/sub', $slug ); ?>
		</header>

		<?php if ( 'traduccion' === $slug && jfz_meta( 'texto_original' ) ) : ?>
			<div class="bilingue">
				<div class="bilingue__col bilingue__col--original" lang="<?php echo esc_attr( jfz_lang_code( jfz_meta( 'idioma' ) ) ); ?>">
					<p class="bilingue__label"><?php echo esc_html( jfz_meta( 'titulo_original' ) ?: __( 'Original', 'juan-felipe-zaldivar' ) ); ?></p>
					<pre class="verso"><?php echo esc_html( jfz_meta( 'texto_original' ) ); ?></pre>
				</div>
				<div class="bilingue__col bilingue__col--traduccion">
					<p class="bilingue__label"><?php esc_html_e( 'Traducción', 'juan-felipe-zaldivar' ); ?></p>
					<div class="entry__content"><?php the_content(); ?></div>
				</div>
			</div>
		<?php else : ?>
			<div class="entry__content">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>

		<footer class="entry__footer">
			<?php the_tags( '<p class="entry__tags">' . esc_html__( 'Etiquetas:', 'juan-felipe-zaldivar' ) . ' ', ', ', '</p>' ); ?>
			<?php
			$prev = jfz_adjacent_same_tipo( true );
			$next = jfz_adjacent_same_tipo( false );
			if ( $prev || $next ) :
				?>
				<nav class="entry-nav" aria-label="<?php esc_attr_e( 'Otros textos del mismo tipo', 'juan-felipe-zaldivar' ); ?>">
					<?php if ( $next ) : ?>
						<a class="entry-nav__link entry-nav__link--next" href="<?php echo esc_url( get_permalink( $next ) ); ?>"><span class="entry-nav__label"><?php esc_html_e( 'Más reciente', 'juan-felipe-zaldivar' ); ?></span><span class="entry-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span></a>
					<?php endif; ?>
					<?php if ( $prev ) : ?>
						<a class="entry-nav__link entry-nav__link--prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>"><span class="entry-nav__label"><?php esc_html_e( 'Anterior', 'juan-felipe-zaldivar' ); ?></span><span class="entry-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span></a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>
			<?php if ( $tipo ) : ?>
				<p class="entry__back"><a href="<?php echo esc_url( get_term_link( $tipo ) ); ?>">← <?php echo esc_html( sprintf( __( 'Todos los textos de %s', 'juan-felipe-zaldivar' ), jfz_tipo_plural( $slug ) ) ); ?></a></p>
			<?php endif; ?>
		</footer>
	</article>
	<?php
endwhile;

get_footer();
