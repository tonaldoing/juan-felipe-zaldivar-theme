<?php
/**
 * Pie.
 *
 * @package juan-felipe-zaldivar
 */
?>
</main>

<footer class="site-footer">
	<div class="wrap">
		<?php get_template_part( 'template-parts/newsletter' ); ?>

		<div class="site-footer__grid">
			<div class="site-footer__col">
				<p class="site-footer__name"><?php bloginfo( 'name' ); ?></p>
				<?php if ( jfz_option( 'pie_texto' ) ) : ?>
					<p class="site-footer__note"><?php echo esc_html( jfz_option( 'pie_texto' ) ); ?></p>
				<?php endif; ?>
				<p class="site-footer__note">© <?php echo esc_html( wp_date( 'Y' ) ); ?></p>
			</div>

			<div class="site-footer__col">
				<ul class="site-footer__links">
					<?php if ( jfz_option( 'email' ) ) : ?>
						<li><a href="mailto:<?php echo esc_attr( antispambot( jfz_option( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( jfz_option( 'email' ) ) ); ?></a></li>
					<?php endif; ?>
					<?php if ( jfz_option( 'instagram' ) ) : ?>
						<li><a href="<?php echo esc_url( jfz_option( 'instagram' ) ); ?>" rel="me noopener" target="_blank">Instagram</a></li>
					<?php endif; ?>
					<?php if ( jfz_option( 'otra_red' ) ) : ?>
						<li><a href="<?php echo esc_url( jfz_option( 'otra_red' ) ); ?>" rel="me noopener" target="_blank"><?php echo esc_html( jfz_option( 'otra_red_nombre' ) ?: __( 'Red social', 'juan-felipe-zaldivar' ) ); ?></a></li>
					<?php endif; ?>
					<li><a href="<?php echo esc_url( get_feed_link() ); ?>">RSS</a></li>
				</ul>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'pie',
						'container'      => false,
						'menu_class'     => 'site-footer__links',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>

			<div class="site-footer__col">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
