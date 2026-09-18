<?php if ( jfz_meta( 'obra_titulo' ) || has_post_thumbnail() ) : ?>
	<div class="ficha">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="ficha__portada"><?php the_post_thumbnail( 'jfz-portada', array( 'loading' => 'eager' ) ); ?></div>
		<?php endif; ?>
		<dl class="ficha__datos">
			<?php if ( jfz_meta( 'obra_titulo' ) ) : ?>
				<dt><?php esc_html_e( 'Libro', 'juan-felipe-zaldivar' ); ?></dt><dd><em><?php echo esc_html( jfz_meta( 'obra_titulo' ) ); ?></em></dd>
			<?php endif; ?>
			<?php if ( jfz_meta( 'obra_autor' ) ) : ?>
				<dt><?php esc_html_e( 'Autor', 'juan-felipe-zaldivar' ); ?></dt><dd><?php echo esc_html( jfz_meta( 'obra_autor' ) ); ?></dd>
			<?php endif; ?>
			<?php if ( jfz_meta( 'obra_editorial' ) || jfz_meta( 'obra_anio' ) ) : ?>
				<dt><?php esc_html_e( 'Edición', 'juan-felipe-zaldivar' ); ?></dt><dd><?php echo esc_html( implode( ', ', array_filter( array( jfz_meta( 'obra_editorial' ), jfz_meta( 'obra_anio' ) ) ) ) ); ?></dd>
			<?php endif; ?>
		</dl>
	</div>
<?php endif; ?>
