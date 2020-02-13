<?php declare( strict_types = 1 );
/**
 * DSS Mime Aliases
 *
 * @package     DSS Mime Aliases
 * @author      Per Soderlind
 * @copyright   2018 Per Soderlind
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: DSS Mime Aliases
 * Plugin URI: https://github.com/soderlind/dss-mime-aliases
 * GitHub Plugin URI: https://github.com/soderlind/dss-mime-aliases
 * Description: Replace mime type 'application/CDFV2[-*]' with the correct, if allowed, mime type.
 * Version:     0.0.4
 * Author:      Per Soderlind
 * Author URI:  https://soderlind.no
 * Text Domain: dss-mime-aliases
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

 namespace Soderlind\Plugin\MimeAliases;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die();
}

add_filter(
	'wp_check_filetype_and_ext',
	function( $wp_check_filetype_and_ext, $file, $filename, $mimes, $real_mime ) {
		if ( ! empty( $wp_check_filetype_and_ext['ext'] ) ) {
			return $wp_check_filetype_and_ext;
		}

		$allowed_mime_types = get_allowed_mime_types();
		/**
		 * HACK: Replace mime type 'application/CDFV2[-*]' with the correct, if allowed, mime type.
		 */
		if ( false !== strpos( $real_mime, 'application/CDFV2' ) ) {
			$ext = strrchr( $filename, '.' );
			$ext = end( explode( '.', basename( $filename ) ) );
			if ( isset( $allowed_mime_types, $allowed_mime_types[ $ext ] ) ) {
				$wp_check_filetype_and_ext = [
					'ext'  => $ext,
					'type' => $allowed_mime_types[ $ext ],
				];
			}
		}

		return $wp_check_filetype_and_ext;
	},
	10,
	5
);
