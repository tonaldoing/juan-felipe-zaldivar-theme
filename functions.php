<?php
/**
 * Tema Juan Felipe Zaldívar.
 *
 * @package juan-felipe-zaldivar
 */

defined( 'ABSPATH' ) || exit;

define( 'JFZ_VERSION', '1.0.0' );
define( 'JFZ_DIR', get_template_directory() );
define( 'JFZ_URI', get_template_directory_uri() );

require JFZ_DIR . '/inc/helpers.php';
require JFZ_DIR . '/inc/setup.php';
require JFZ_DIR . '/inc/enqueue.php';
require JFZ_DIR . '/inc/taxonomies.php';
require JFZ_DIR . '/inc/post-types.php';
require JFZ_DIR . '/inc/meta.php';
require JFZ_DIR . '/inc/queries.php';
require JFZ_DIR . '/inc/theme-options.php';
require JFZ_DIR . '/inc/seo.php';
require JFZ_DIR . '/inc/security.php';
require JFZ_DIR . '/inc/demo-content.php';
