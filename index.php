<?php
/**
 * Portada y archivos (tipo, etiqueta, fecha): el flujo cronológico.
 *
 * @package juan-felipe-zaldivar
 */

get_header();

if ( is_front_page() || is_home() ) {
	get_template_part( 'template-parts/evento/aviso' );
}
?>

<?php if ( is_archive() ) : ?>
	<header class="page-header">
		<?php if ( is_tax( 'tipo' ) ) : ?>
			<h1 class="page-header__title"><?php echo esc_html( jfz_tipo_plural( get_queried_object()->slug ) ); ?></h1>
			<?php if ( term_description() ) : ?>
				<div class="page-header__desc"><?php echo wp_kses_post( term_description() ); ?></div>
			<?php endif; ?>
		<?php elseif ( is_tag() ) : ?>
			<h1 class="page-header__title"><?php esc_html_e( 'Etiqueta:', 'juan-felipe-zaldivar' ); ?> <?php single_tag_title(); ?></h1>
		<?php else : ?>
			<h1 class="page-header__title"><?php the_archive_title(); ?></h1>
		<?php endif; ?>
	</header>
<?php endif; ?>

<?php get_template_part( 'template-parts/tipos-nav' ); ?>

<?php if ( have_posts() ) : ?>
	<div class="flow">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/entry/card' );
		endwhile;
		?>
	</div>
	<?php
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => __( '← Más recientes', 'juan-felipe-zaldivar' ),
			'next_text' => __( 'Más antiguos →', 'juan-felipe-zaldivar' ),
			'screen_reader_text' => __( 'Paginación', 'juan-felipe-zaldivar' ),
		)
	);
	?>
<?php else : ?>
	<p class="empty"><?php esc_html_e( 'Todavía no hay textos publicados.', 'juan-felipe-zaldivar' ); ?></p>
<?php endif; ?>

<?php get_footer(); ?>
