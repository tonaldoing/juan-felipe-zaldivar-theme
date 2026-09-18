/* Muestra solo el grupo de campos del tipo de texto elegido. */
( function () {
	function sync() {
		var checked = document.querySelector( 'input[name="jfz_tipo"]:checked' );
		if ( ! document.querySelector( 'input[name="jfz_tipo"]' ) ) {
			return;
		}
		var tipo = checked ? checked.value : 'nota';
		document.querySelectorAll( '.jfz-meta-group[data-tipo]' ).forEach( function ( group ) {
			group.hidden = group.getAttribute( 'data-tipo' ) !== tipo;
		} );
	}
	function bind() {
		document.querySelectorAll( 'input[name="jfz_tipo"]' ).forEach( function ( radio ) {
			radio.addEventListener( 'change', sync );
		} );
		sync();
	}
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', bind );
	} else {
		bind();
	}
	// El editor de bloques monta el meta box tarde; se reintenta un rato.
	var tries = 0;
	var timer = setInterval( function () {
		tries++;
		if ( document.querySelector( 'input[name="jfz_tipo"]' ) ) {
			bind();
			clearInterval( timer );
		}
		if ( tries > 40 ) {
			clearInterval( timer );
		}
	}, 250 );
} )();
