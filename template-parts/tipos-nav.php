<?php
/**
 * Filtro por tipo de texto (chips). Marca el tipo actual en archivos de tipo.
 *
 * @package juan-felipe-zaldivar
 */

$actual = is_tax( 'tipo' ) ? get_queried_object()->slug : '';
?>
<nav class="tipos" aria-label="<?php esc_attr_e( 'Tipos de texto', 'juan-felipe-zaldivar' ); ?>">
	<ul class="tipos__list">
		<li><a class="tipos__link <?php echo $actual ? '' : 'is-active'; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo $actual ? '' : 'aria-current="page"'; ?>><?php esc_html_e( 'Todo', 'juan-felipe-zaldivar' ); ?></a></li>
		<?php foreach ( jfz_tipos() as $slug => $label ) : ?>
			<?php
			$term = get_term_by( 'slug', $slug, 'tipo' );
			if ( ! $term || ! $term->count ) {
				continue;
			}
			?>
			<li><a class="tipos__link <?php echo $actual === $slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $term ) ); ?>" <?php echo $actual === $slug ? 'aria-current="page"' : ''; ?>><?php echo esc_html( jfz_tipo_plural( $slug ) ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</nav>
