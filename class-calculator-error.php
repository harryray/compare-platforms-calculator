<?php

/**
 * WPuacc_Error
 *
 * @package     Calculator_Error
 * @subpackage
 * @copyright   Copyright (c) 2015, Netbrained Ltd
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

class Calculator_Error {

	private $error;
	private $notice;
	private $success;

	/**
	 * Calculator_Error constructor.
	 */
	public function __construct() {

	}

	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new Calculator_Error;
			$instance->setup();
		}


		// Always return the instance
		return $instance;
	}

	public function setup() {
		$this->error = new WP_Error( null, null, null );
		$this->notice = new WP_Error( null, null, null );
		$this->success = new WP_Error( null, null, null );
	}

	public function add_error($code ,$message) {
		$this->error->add($code ,$message);
	}

	public function add_notice($code ,$message) {
		$this->notice->add($code ,$message);
	}

	public function add_success($code ,$message) {
		$this->success->add($code ,$message);
	}

	public function get_error_message($code) {
		return $this->error->get_error_message($code);
	}
	public function get_error_messages() {
		return $this->error->get_error_messages();
	}

	public function get_notice_messages() {
		return $this->notice->get_error_messages();
	}

	public function get_success_messages() {
		return $this->success->get_error_messages();
	}

	function display_errors() {

		$errors = $this->get_error_messages();
		$notices = $this->get_notice_messages();
		$success = $this->get_success_messages();

		ob_start();
		include( $template_loader->locate_template( "error-notice-template.php" ) );
		return ob_get_clean();
	}
}

function ctp_errors() {
	return Calculator_Error::instance();
}

ctp_errors();