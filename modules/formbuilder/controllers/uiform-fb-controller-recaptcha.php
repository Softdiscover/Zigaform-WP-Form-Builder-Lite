<?php

/**
 * Intranet
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2015 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      https://wordpress-form-builder.zigaform.com/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
if ( class_exists( 'Uiform_Fb_Controller_Recaptcha' ) ) {
	return;
}

/**
 * Controller Recaptcha class
 *
 * @category  PHP
 * @package   Rocket_form
 * @author    Softdiscover <info@softdiscover.com>
 * @copyright 2013 Softdiscover
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   Release: 1.00
 * @link      https://wordpress-form-builder.zigaform.com/
 */
class Uiform_Fb_Controller_Recaptcha extends Uiform_Base_Module {

	const VERSION = '0.1';

	private $model_fields = '';
	private $formsmodel        = '';
	/*
	 * Magic methods
	 */

	/**
	 * Constructor
	 *
	 * @mvc Controller
	 */
	protected function __construct() {
		$this->model_fields = self::$_models['formbuilder']['fields'];
		$this->formsmodel        = self::$_models['formbuilder']['form'];
	}


	/**
	 * Recaptcha::front_verify_recaptcha()
	 *
	 * @return
	 */
	public function front_verify_recaptcha() {

		require_once UIFORM_FORMS_LIBS . '/recaptcha2/appengine-https.php';
		require_once UIFORM_FORMS_LIBS . '/recaptcha2/autoload.php';

		$uid_field = ( isset( $_POST['rockfm-uid-field'] ) ) ? Uiform_Form_Helper::sanitizeInput( $_POST['rockfm-uid-field'] ) : '';
		$form_id   = ( isset( $_POST['form_id'] ) ) ? Uiform_Form_Helper::sanitizeInput( $_POST['form_id'] ) : 0;
		$fmf_json  = $this->model_fields->getDataByUniqueId( $uid_field, $form_id );
		$secret    = '';
		$success   = false;
		if ( ! empty( $fmf_json ) ) {
			$fmf_data = json_decode( $fmf_json->fmf_data, true );

			$secret  = ( isset( $fmf_data['input5']['g_key_secret'] ) ) ? $fmf_data['input5']['g_key_secret'] : '';
			$siteKey = ( isset( $fmf_data['input5']['g_key_public'] ) ) ? $fmf_data['input5']['g_key_public'] : '';

			if ( $siteKey === '' || $secret === '' ) {

			} elseif ( isset( $_POST['rockfm-code-recaptcha'] ) ) {

				$recaptcha = new \ReCaptcha\ReCaptcha( $secret );

				// If file_get_contents() is locked down on your PHP installation to disallow
				// its use with URLs, then you can use the alternative request method instead.
				// This makes use of fsockopen() instead.
				// $recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());
				// Make the call to verify the response and also pass the user's IP address
				$resp = $recaptcha->setExpectedHostname( $_SERVER['SERVER_NAME'] )
								  ->verify( $_POST['rockfm-code-recaptcha'], $_SERVER['REMOTE_ADDR'] );

				if ( $resp->isSuccess() ) :
					$success = true;
				else :
					$success = false;
				endif;

				// in case false, using method 2 to get validation
				if ( $success === false ) {
					if ( is_callable( 'curl_init' ) ) {
						$recaptcha = new \ReCaptcha\ReCaptcha( $secret, new \ReCaptcha\RequestMethod\CurlPost() );

						$resp = $recaptcha->setExpectedHostname( $_SERVER['SERVER_NAME'] )
								  ->verify( $_POST['rockfm-code-recaptcha'], $_SERVER['REMOTE_ADDR'] );
						if ( $resp->isSuccess() ) :
							$success = true;
						else :
							$success = false;
						endif;

					}
				}
			} else {

			}
		}

		$json            = array();
		$json['success'] = $success;
		// return data to ajax callback
		header( 'Content-Type: application/json' );
		echo json_encode( $json );
		die();
	}

	/**
	 * Recaptcha::front_verify_recaptcha()
	 *
	 * @return
	 */
	public function front_verify_recaptchav3() {

		
		require_once UIFORM_FORMS_LIBS . '/recaptcha/1.3.0/src/autoload.php';
		
		$form_id   = ( isset( $_POST['form_id'] ) ) ? Uiform_Form_Helper::sanitizeInput( $_POST['form_id'] ) : 0;
		
		$data_form = $this->formsmodel->getAvailableFormById( $form_id );
		$onsubm = json_decode( $data_form->fmb_data2, true );
		$secret= $onsubm['main']['recaptchav3_secretkey']??'';
		$gRecaptchaResponse = ( isset( $_POST['zgfm_token'] ) ) ? Uiform_Form_Helper::sanitizeInput( $_POST['zgfm_token'] ) : '';
		$recaptcha = new \ReCaptcha\ReCaptcha($secret);
		$remoteIp = $_SERVER['REMOTE_ADDR'];
		$success   = false;
		$errors = [];
		$resp = $recaptcha->setExpectedHostname($_SERVER['HTTP_HOST'])
                  ->verify($gRecaptchaResponse, $remoteIp);
		if ($resp->isSuccess()) {
		    $success = true;
		} else {
			$success = true;
		    $errors = $resp->getErrorCodes();
		}
		
		
		$json            = array();
		$json['success'] = $success;
		$json['error'] =  $errors;
		// return data to ajax callback
		header( 'Content-Type: application/json' );
		echo json_encode( $json );
		die();
	}

	/**
	 * Register callbacks for actions and filters
	 *
	 * @mvc Controller
	 */
	public function register_hook_callbacks() {

	}

	/**
	 * Initializes variables
	 *
	 * @mvc Controller
	 */
	public function init() {

		try {
			// $instance_example = new WPPS_Instance_Class( 'Instance example', '42' );
			// add_notice('ba');
		} catch ( Exception $exception ) {
			add_notice( __METHOD__ . ' error: ' . $exception->getMessage(), 'error' );
		}
	}

	/*
	 * Instance methods
	 */

	/**
	 * Prepares sites to use the plugin during single or network-wide activation
	 *
	 * @mvc Controller
	 *
	 * @param bool $network_wide
	 */
	public function activate( $network_wide ) {

		return true;
	}

	/**
	 * Rolls back activation procedures when de-activating the plugin
	 *
	 * @mvc Controller
	 */
	public function deactivate() {
		return true;
	}

	/**
	 * Checks if the plugin was recently updated and upgrades if necessary
	 *
	 * @mvc Controller
	 *
	 * @param string $db_version
	 */
	public function upgrade( $db_version = 0 ) {
		return true;
	}

	/**
	 * Checks that the object is in a correct state
	 *
	 * @mvc Model
	 *
	 * @param string $property An individual property to check, or 'all' to check all of them
	 * @return bool
	 */
	protected function is_valid( $property = 'all' ) {
		return true;
	}

}


