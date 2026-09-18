<?php
/**
 * Opciones del sitio: newsletter, contacto, redes, pie.
 * Pantalla en Apariencia → Opciones del sitio.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

function jfz_option_fields() {
	return array(
		'newsletter' => array(
			'title'  => __( 'Newsletter', 'juan-felipe-zaldivar' ),
			'intro'  => __( 'El formulario envía el email al proveedor que uses (Buttondown, Mailchimp, Substack, etc.). Pegá la URL de acción de su formulario de suscripción y el nombre del campo de email. Si la URL queda vacía, la caja no se muestra.', 'juan-felipe-zaldivar' ),
			'fields' => array(
				'newsletter_titulo' => array( 'label' => __( 'Título de la caja', 'juan-felipe-zaldivar' ), 'type' => 'text', 'default' => 'Recibir los textos por correo' ),
				'newsletter_texto'  => array( 'label' => __( 'Texto corto', 'juan-felipe-zaldivar' ), 'type' => 'text', 'default' => 'Cada texto nuevo llega a tu casilla. Sin spam, te das de baja cuando quieras.' ),
				'newsletter_url'    => array( 'label' => __( 'URL de acción del formulario', 'juan-felipe-zaldivar' ), 'type' => 'url', 'placeholder' => 'https://buttondown.com/api/emails/embed-subscribe/usuario' ),
				'newsletter_campo'  => array( 'label' => __( 'Nombre del campo de email', 'juan-felipe-zaldivar' ), 'type' => 'text', 'default' => 'email', 'desc' => 'Buttondown y Substack usan "email"; Mailchimp usa "EMAIL".' ),
				'newsletter_metodo' => array( 'label' => __( 'Método', 'juan-felipe-zaldivar' ), 'type' => 'select', 'options' => array( 'post' => 'POST', 'get' => 'GET' ), 'default' => 'post' ),
			),
		),
		'contacto'   => array(
			'title'  => __( 'Contacto y redes', 'juan-felipe-zaldivar' ),
			'intro'  => __( 'Se muestran en el pie y en la página Sobre mí. Dejá vacío lo que no uses.', 'juan-felipe-zaldivar' ),
			'fields' => array(
				'email'     => array( 'label' => __( 'Email de contacto', 'juan-felipe-zaldivar' ), 'type' => 'email' ),
				'instagram' => array( 'label' => __( 'Instagram (URL)', 'juan-felipe-zaldivar' ), 'type' => 'url' ),
				'otra_red'  => array( 'label' => __( 'Otra red (URL)', 'juan-felipe-zaldivar' ), 'type' => 'url' ),
				'otra_red_nombre' => array( 'label' => __( 'Nombre de la otra red', 'juan-felipe-zaldivar' ), 'type' => 'text', 'placeholder' => 'YouTube, X, Bluesky…' ),
			),
		),
		'textos'     => array(
			'title'  => __( 'Textos del sitio', 'juan-felipe-zaldivar' ),
			'fields' => array(
				'pie_texto'    => array( 'label' => __( 'Texto del pie', 'juan-felipe-zaldivar' ), 'type' => 'text', 'default' => 'Los textos de este sitio pueden citarse mencionando la fuente.' ),
				'aviso_evento' => array( 'label' => __( 'Etiqueta del aviso de evento en portada', 'juan-felipe-zaldivar' ), 'type' => 'text', 'default' => 'Próximo evento' ),
			),
		),
	);
}

/**
 * Lee una opción con su valor por defecto.
 */
function jfz_option( $key ) {
	$opts = get_option( 'jfz_options', array() );
	if ( isset( $opts[ $key ] ) && '' !== $opts[ $key ] ) {
		return $opts[ $key ];
	}
	foreach ( jfz_option_fields() as $section ) {
		if ( isset( $section['fields'][ $key ]['default'] ) ) {
			return $section['fields'][ $key ]['default'];
		}
	}
	return '';
}

add_action( 'admin_menu', 'jfz_options_menu' );
function jfz_options_menu() {
	add_theme_page( __( 'Opciones del sitio', 'juan-felipe-zaldivar' ), __( 'Opciones del sitio', 'juan-felipe-zaldivar' ), 'manage_options', 'jfz-options', 'jfz_options_page' );
}

add_action( 'admin_init', 'jfz_options_init' );
function jfz_options_init() {
	register_setting( 'jfz_options', 'jfz_options', array( 'sanitize_callback' => 'jfz_options_sanitize' ) );
}

function jfz_options_sanitize( $input ) {
	$clean = array();
	$input = is_array( $input ) ? $input : array();
	foreach ( jfz_option_fields() as $section ) {
		foreach ( $section['fields'] as $key => $field ) {
			$value = isset( $input[ $key ] ) ? trim( (string) $input[ $key ] ) : '';
			switch ( $field['type'] ) {
				case 'url':
					$value = esc_url_raw( $value );
					break;
				case 'email':
					$value = sanitize_email( $value );
					break;
				case 'select':
					$value = isset( $field['options'][ $value ] ) ? $value : ( $field['default'] ?? '' );
					break;
				default:
					$value = sanitize_text_field( $value );
			}
			$clean[ $key ] = $value;
		}
	}
	return $clean;
}

function jfz_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opts = get_option( 'jfz_options', array() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Opciones del sitio', 'juan-felipe-zaldivar' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'jfz_options' ); ?>
			<?php foreach ( jfz_option_fields() as $section ) : ?>
				<h2><?php echo esc_html( $section['title'] ); ?></h2>
				<?php if ( ! empty( $section['intro'] ) ) : ?>
					<p class="description"><?php echo esc_html( $section['intro'] ); ?></p>
				<?php endif; ?>
				<table class="form-table" role="presentation">
					<?php foreach ( $section['fields'] as $key => $field ) : ?>
						<?php $value = $opts[ $key ] ?? ( $field['default'] ?? '' ); ?>
						<tr>
							<th scope="row"><label for="jfz_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
							<td>
								<?php if ( 'select' === $field['type'] ) : ?>
									<select id="jfz_<?php echo esc_attr( $key ); ?>" name="jfz_options[<?php echo esc_attr( $key ); ?>]">
										<?php foreach ( $field['options'] as $v => $l ) : ?>
											<option value="<?php echo esc_attr( $v ); ?>" <?php selected( $value, $v ); ?>><?php echo esc_html( $l ); ?></option>
										<?php endforeach; ?>
									</select>
								<?php else : ?>
									<input class="regular-text" type="<?php echo esc_attr( $field['type'] ); ?>" id="jfz_<?php echo esc_attr( $key ); ?>" name="jfz_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ?? '' ); ?>">
								<?php endif; ?>
								<?php if ( ! empty( $field['desc'] ) ) : ?>
									<p class="description"><?php echo esc_html( $field['desc'] ); ?></p>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
