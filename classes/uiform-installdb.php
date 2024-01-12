<?php

/**
 * Frontend
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
if ( class_exists( 'Uiform_InstallDB' ) ) {
	return;
}

class Uiform_InstallDB {


	private $form;
	private $form_history;
	private $form_fields;
	private $form_log;
	private $form_fields_type;
	private $settings;
	private $nopre_form;
	private $nopre_form_history;
	private $nopre_form_fields;
	private $nopre_form_fields_type;
	private $nopre_settings;
	private $core_addon;
	private $core_addon_detail;
	private $core_addon_log;

	public function __construct() {
		 global $wpdb;
		$this->form                   = $wpdb->prefix . 'uiform_form';
		$this->form_history           = $wpdb->prefix . 'uiform_form_records';
		$this->form_fields            = $wpdb->prefix . 'uiform_fields';
		$this->form_log               = $wpdb->prefix . 'uiform_form_log';
		$this->form_fields_type       = $wpdb->prefix . 'uiform_fields_type';
		$this->settings               = $wpdb->prefix . 'uiform_settings';
		$this->nopre_form             = 'uiform_form';
		$this->nopre_form_history     = 'uiform_form_records';
		$this->nopre_form_fields      = 'uiform_fields';
		$this->nopre_form_fields_type = 'uiform_fields_type';
		$this->nopre_settings         = 'uiform_settings';
		$this->core_addon             = $wpdb->prefix . 'uiform_addon';
		$this->core_addon_detail      = $wpdb->prefix . 'uiform_addon_details';
		$this->core_addon_log         = $wpdb->prefix . 'uiform_addon_details_log';
	}

	public function install( $networkwide = false ) {
		if ( $networkwide ) {
			deactivate_plugins( plugin_basename( UIFORM_ABSFILE ) );
			wp_die( __( 'The plugin can not be network activated. You need to activate the plugin per site.', 'FRocket_admin' ) );
		}
		global $wpdb;
		$charset = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$charset = "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if ( ! empty( $wpdb->collate ) ) {
				$charset .= " COLLATE $wpdb->collate";
			}
		}
		
		// forms
		if(  version_compare( $wpdb->db_version(), '8.0', '>=' ) ){
			 require_once(__DIR__ . '/mysql8.php');
		}else{
			require_once(__DIR__ . '/mysql.php');
		}
		
		// Store the date when the initial activation was performed
		$type      = class_exists( 'UiformFormbuilderLite' ) ? 'pro' : 'lite';
		$activated = get_option( 'zgfm_b_activated', array() );
		if ( empty( $activated[ $type ] ) ) {
			$activated[ $type ] = time();
			update_option( 'zgfm_b_activated', $activated );
		}

		// ajax mode by default
		update_option( 'zgfm_b_modalmode', 0 );
	}

	public function uninstall() {
		global $wpdb;
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->form_history );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->form_fields );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->form_log );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->form_fields_type );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->form );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->settings );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->core_addon );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->core_addon_detail );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $this->core_addon_log );

		// removing options
		delete_option( 'uifmfbuild_version' );
	}
}
