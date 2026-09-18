<?php
/**
 * Cabecera.
 *
 * @package juan-felipe-zaldivar
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="light dark">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#contenido"><?php esc_html_e( 'Ir al contenido', 'juan-felipe-zaldivar' ); ?></a>

<header class="site-header">
	<div class="wrap site-header__inner">
		<div class="site-header__brand">
			<?php if ( is_front_page() ) : ?>
				<h1 class="wordmark"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="wordmark"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></p>
			<?php endif; ?>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="tagline"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
		</div>
		<nav class="nav" aria-label="<?php esc_attr_e( 'Principal', 'juan-felipe-zaldivar' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'principal',
					'container'      => false,
					'menu_class'     => 'nav__list',
					'depth'          => 1,
					'fallback_cb'    => 'jfz_fallback_menu',
				)
			);
			?>
		</nav>
	</div>
</header>

<main id="contenido" class="site-main wrap">
