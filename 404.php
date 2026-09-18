<?php
/**
 * Página no encontrada.
 *
 * @package juan-felipe-zaldivar
 */

get_header();
?>
<header class="page-header">
	<h1 class="page-header__title"><?php esc_html_e( 'Esta página no existe', 'juan-felipe-zaldivar' ); ?></h1>
	<p class="page-header__desc"><?php esc_html_e( 'Puede que el texto se haya movido o que el enlace esté mal escrito.', 'juan-felipe-zaldivar' ); ?></p>
	<?php get_search_form(); ?>
</header>
<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">← <?php esc_html_e( 'Volver a la portada', 'juan-felipe-zaldivar' ); ?></a></p>
<?php get_footer(); ?>
