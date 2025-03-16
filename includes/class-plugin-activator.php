<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Disposable_Email_Blocker_Gravityforms
 * @subpackage Disposable_Email_Blocker_Gravityforms/includes
 * @author     Sajjad Hossain Sagor <sagorh672@gmail.com>
 */
class Disposable_Email_Blocker_Gravityforms_Activator
{
	/**
	 * Handles plugin activation tasks.
	 *
	 * This static function is called when the plugin is activated. It creates the
	 * necessary database table to store disposable email domains and populates it
	 * with data from a txt file.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @return   void
	 */
	public static function activate()
	{
		if ( ! wp_next_scheduled( 'gform_create_disposable_email_domains_table' ) )
		{
			wp_schedule_single_event( time() + 10, 'gform_create_disposable_email_domains_table' );
		}
	}
}
