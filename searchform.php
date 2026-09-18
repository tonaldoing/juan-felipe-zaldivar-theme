<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="search-form__label" for="search-field"><?php esc_html_e( 'Buscar en el sitio', 'juan-felipe-zaldivar' ); ?></label>
	<div class="search-form__row">
		<input class="search-form__input" type="search" id="search-field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Un título, una palabra…', 'juan-felipe-zaldivar' ); ?>">
		<button class="button button--small" type="submit"><?php esc_html_e( 'Buscar', 'juan-felipe-zaldivar' ); ?></button>
	</div>
</form>
