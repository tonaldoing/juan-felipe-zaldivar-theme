<?php if ( jfz_meta( 'obra_titulo' ) || has_post_thumbnail() ) : ?>
	<div class="ficha">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="ficha__portada"><?php the_post_thumbnail( 'jfz-portada', array( 'loading' => 'eager' ) ); ?></div>
		<?php endif; ?>
		<dl class="ficha__datos">
			<?php if ( jfz_meta( 'obra_titulo' ) ) : ?>
				<dt><?php esc_html_e( 'Película', 'juan-felipe-zaldivar' ); ?></dt><dd><em><?php echo esc_html( jfz_meta( 'obra_titulo' ) ); ?></em></dd>
			<?php endif; ?>
			<?php if ( jfz_meta( 'obra_director' ) ) : ?>
				<dt><?php esc_html_e( 'Dirección', 'juan-felipe-zaldivar' ); ?></dt><dd><?php echo esc_html( jfz_meta( 'obra_director' ) ); ?></dd>
			<?php endif; ?>
			<?php if ( jfz_meta( 'obra_anio' ) ) : ?>
				<dt><?php esc_html_e( 'Año', 'juan-felipe-zaldivar' ); ?></dt><dd><?php echo esc_html( jfz_meta( 'obra_anio' ) ); ?></dd>
			<?php endif; ?>
		</dl>
	</div>
<?php endif; ?>
