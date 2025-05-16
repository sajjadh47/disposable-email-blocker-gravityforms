<?php
/**
 * This file contains the definition of the Disposable_Email_Blocker_Gravityforms_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Disposable_Email_Blocker_Gravityforms
 * @subpackage    Disposable_Email_Blocker_Gravityforms/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Disposable_Email_Blocker_Gravityforms_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Validates email fields in Gravity Forms to block disposable emails.
	 *
	 * This function checks if the submitted email address in a Gravity Forms email field
	 * belongs to a list of disposable email domains. It first checks a database table
	 * (if it exists) and then falls back to a text file if the table is not found.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array    $result The current validation result array.
	 * @param     string   $value  The submitted value of the field.
	 * @param     array    $form   The current Gravity Forms form array.
	 * @param     GF_Field $field  The current Gravity Forms field object.
	 * @return    array            The modified validation result array.
	 */
	public function gform_field_validation( $result, $value, $form, $field ) {
		global $wpdb;

		// if not blocking is enabled return early.
		if ( empty( $form['block_disposable_emails'] ) || '1' !== $form['block_disposable_emails'] ) {
			return $result;
		}

		if ( 'email' === $field->get_input_type() ) {
			$msg = ( empty( $form['disposable_emails_found_msg'] ) ) ? __( 'Disposable/Temporary emails are not allowed! Please use a non temporary email', 'disposable-email-blocker-gravityforms' ) : sanitize_text_field( $form['disposable_emails_found_msg'] );

			// fallback to when confirm email is also checked and it returns array of both emails.
			$email = is_array( $value ) ? array_shift( $value ) : $value;

			if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				// split on @ and return last value of array (the domain).
				$domain     = explode( '@', sanitize_email( $email ) );
				$domain     = array_pop( $domain );
				$found      = false;
				$table_name = $wpdb->prefix . DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_TABLE_NAME;
				$txt_file   = DISPOSABLE_EMAIL_BLOCKER_GRAVITYFORMS_PLUGIN_PATH . '/public/data/domains.txt';

				// Check if the table exists.
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$table_exists = $wpdb->get_var(
					$wpdb->prepare(
						'SHOW TABLES LIKE %s',
						$table_name
					)
				);

				if ( $table_exists ) {
					// Look for the domain in the database.
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$found = (bool) $wpdb->get_var(
						$wpdb->prepare(
							// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
							"SELECT COUNT(*) FROM {$table_name} WHERE domain = %s",
							$domain
						)
					);
				} elseif ( file_exists( $txt_file ) ) { // If not found the table and file exists, fall back to txt.
					global $wp_filesystem;

					if ( ! $wp_filesystem ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
					}

					WP_Filesystem();

					// Get domains list from the txt file.
					$txt_file_content   = $wp_filesystem->get_contents( $txt_file );
					$disposable_domains = explode( "\n", $txt_file_content );

					if ( is_array( $disposable_domains ) && in_array( $domain, $disposable_domains, true ) ) {
						$found = true;
					}
				}

				// If found in DB or txt, invalidate the result.
				if ( $found ) {
					$result['is_valid'] = false;
					$result['message']  = $msg;
				}
			}
		}

		return $result;
	}
}
