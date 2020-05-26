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


if ( version_compare( $version, $install_ver, '>' ) ) {

	if ( version_compare( $install_ver, '1.7.3.6', '<' ) ) {
		$tbname = $wpdb->prefix . 'uiform_form_records';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) === $tbname ) {
			$row = $wpdb->get_var( 'SHOW COLUMNS FROM ' . $tbname . " LIKE 'fbh_data_rec'" );
			if ( empty( $row ) ) {
				$sql = 'ALTER TABLE ' . $tbname . ' ADD  fbh_data_rec longtext NOT NULL;';
				$wpdb->query( $sql );
			}

			$row = $wpdb->get_var( 'SHOW COLUMNS FROM ' . $tbname . " LIKE 'fbh_data_xml'" );
			if ( ! empty( $row ) ) {
				$sql = 'ALTER TABLE ' . $tbname . ' CHANGE fbh_data_xml fbh_data_rec_xml longtext;';
				$wpdb->query( $sql );
			}
		}
	}


	if ( version_compare( $install_ver, '1.9', '<' ) ) {
		$tbname = $wpdb->prefix . 'uiform_fields';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) === $tbname ) {

			$row = $wpdb->get_var( 'SHOW COLUMNS FROM ' . $tbname . " LIKE 'order_frm'" );


			if ( empty( $row ) ) {
				$sql = 'ALTER TABLE ' . $tbname . ' ADD  order_frm smallint(5) DEFAULT NULL;';
				$wpdb->query( $sql );
			}

			$row = $wpdb->get_var( 'SHOW COLUMNS FROM ' . $tbname . " LIKE 'order_rec'" );

			if ( empty( $row ) ) {
				$sql = 'ALTER TABLE ' . $tbname . ' ADD  order_rec smallint(5) DEFAULT NULL;';
				$wpdb->query( $sql );
			}
		}
	}

	if ( version_compare( $install_ver, '3', '<' ) ) {

		$tbname = $wpdb->prefix . 'uiform_form_log';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) != $tbname ) {
				$charset = '';
				// form log
				$sql = "CREATE  TABLE IF NOT EXISTS $tbname (
                                `log_id` int(6) NOT NULL AUTO_INCREMENT,
                                `log_frm_data` longtext,
                                `log_frm_name` varchar(255) DEFAULT NULL,
                                `log_frm_html` longtext,
                                `log_frm_html_backend` longtext,
                                `log_frm_html_css` longtext,
                                `log_frm_id` int(6) NOT NULL,
                                `log_frm_hash` varchar(255) NOT NULL,
                                `flag_status` smallint(5) DEFAULT '1',
                                `created_date` timestamp NULL,
                                `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `created_ip` varchar(100) DEFAULT NULL,
                                `updated_ip` varchar(100) DEFAULT NULL,
                                `created_by` varchar(100) DEFAULT NULL,
                                `updated_by` varchar(100) DEFAULT NULL,
                                PRIMARY KEY (`log_id`)
                            ) " . $charset . ';';


				$wpdb->query( $sql );

		}
	}

	   // below 3.7
	if ( version_compare( $install_ver, '3.7', '<' ) ) {

		$charset = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$charset = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ) {
				$charset .= " COLLATE $wpdb->collate";
			}
		}


		$tbname = $wpdb->prefix . 'uiform_addon';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) != $tbname ) {

			 // addon
			$sql = "CREATE  TABLE IF NOT EXISTS $tbname (
                            `add_name` varchar(45) NOT NULL DEFAULT '',
                            `add_title` text ,
                            `add_info` text ,
                            `add_system` smallint(5) DEFAULT NULL,
                            `add_hasconfig` smallint(5) DEFAULT NULL,
                            `add_version` varchar(45)  DEFAULT NULL,
                            `add_icon` text ,
                            `add_installed` smallint(5) DEFAULT NULL,
                            `add_order` int(5) DEFAULT NULL,
                            `add_params` text ,
                            `add_log` text ,
                            `addonscol` varchar(45) DEFAULT NULL,
                            `flag_status` smallint(5)  DEFAULT 1,
                            `created_date` timestamp NULL,
                            `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            `created_ip` varchar(100)  DEFAULT NULL,
                            `updated_ip` varchar(100)  DEFAULT NULL,
                            `created_by` varchar(100) DEFAULT NULL,
                            `updated_by` varchar(100) DEFAULT NULL,
                            `add_xml` text ,
                            `add_load_back` smallint(5) DEFAULT NULL,
                            `add_load_front` smallint(5) DEFAULT NULL,
                            `is_field` smallint(5) DEFAULT NULL,
                            PRIMARY KEY (`add_name`) 
                        ) " . $charset . ';';

			 $wpdb->query( $sql );

			if ( ZIGAFORM_F_LITE != 1 ) {
					 $sql = "INSERT INTO $tbname VALUES ('func_anim', 'Animation effect', 'Animation effects to fields', 1, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, '1980-01-01 00:00:01', '2018-01-31 10:35:14', NULL, NULL, NULL, NULL, NULL, 1, 1, 1);";
					 $wpdb->query( $sql );
			}
		}

		$tbname = $wpdb->prefix . 'uiform_addon_details';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) != $tbname ) {
			  // addon detail
				$sql = "CREATE  TABLE IF NOT EXISTS $tbname (
                                `add_name` varchar(45)  NOT NULL,
                                `fmb_id` int(5) NOT NULL,
                                `adet_data` longtext ,
                                `flag_status` smallint(5) DEFAULT 1,
                                `created_date` timestamp NULL,
                                `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `created_ip` varchar(100) DEFAULT NULL,
                                `updated_ip` varchar(100) DEFAULT NULL,
                                `created_by` varchar(100) DEFAULT NULL,
                                `updated_by` varchar(100) DEFAULT NULL,
                                PRIMARY KEY (`add_name`, `fmb_id`) 
                            ) " . $charset . ';';

				 $wpdb->query( $sql );

		}

		$tbname = $wpdb->prefix . 'uiform_addon_details_log';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) != $tbname ) {

			// addon log
			$sql = "CREATE  TABLE IF NOT EXISTS $tbname (
                            `add_log_id` int(5) NOT NULL AUTO_INCREMENT,
                            `add_name` varchar(45)  NOT NULL,
                            `fmb_id` int(5) NOT NULL,
                            `adet_data` longtext  NULL,
                            `flag_status` smallint(5) DEFAULT 1,
                            `created_date` timestamp NULL,
                            `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            `created_ip` varchar(100) DEFAULT NULL,
                            `updated_ip` varchar(100) DEFAULT NULL,
                            `created_by` varchar(100) DEFAULT NULL,
                            `updated_by` varchar(100) DEFAULT NULL,
                            `log_id` int(5) NOT NULL,
                            PRIMARY KEY (`add_log_id`) 
                        ) " . $charset . ';';

			 $wpdb->query( $sql );
		}
	}

	// below 3.7.6.3
	if ( version_compare( $install_ver, '3.7.6.3', '<' ) ) {

		 $tbname = $wpdb->prefix . 'uiform_addon';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) === $tbname ) {

			try {
				$row = $wpdb->get_var( 'SHOW COLUMNS FROM ' . $tbname . " LIKE 'add_id'" );
			} catch ( Exception $e ) {
				$row = array();
			}
			if ( ! empty( $row ) ) {
				$sql = 'ALTER TABLE ' . $tbname . " DROP COLUMN 'add_id';";
				$wpdb->query( $sql );
			}
		}
	}
	// below 3.7
	if ( version_compare( $install_ver, '3.7.7', '<' ) ) {

			$charset = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$charset = "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ) {
				$charset .= " COLLATE $wpdb->collate";
			}
		}


			$tbname = $wpdb->prefix . 'uiform_addon';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) != $tbname ) {

						 // addon
						$sql = "CREATE  TABLE IF NOT EXISTS $tbname (
                                `add_name` varchar(45) NOT NULL DEFAULT '',
                                `add_title` text ,
                                `add_info` text ,
                                `add_system` smallint(5) DEFAULT NULL,
                                `add_hasconfig` smallint(5) DEFAULT NULL,
                                `add_version` varchar(45)  DEFAULT NULL,
                                `add_icon` text ,
                                `add_installed` smallint(5) DEFAULT NULL,
                                `add_order` int(5) DEFAULT NULL,
                                `add_params` text ,
                                `add_log` text ,
                                `addonscol` varchar(45) DEFAULT NULL,
                                `flag_status` smallint(5)  DEFAULT 1,
                                `created_date` timestamp NULL,
                                `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `created_ip` varchar(100)  DEFAULT NULL,
                                `updated_ip` varchar(100)  DEFAULT NULL,
                                `created_by` varchar(100) DEFAULT NULL,
                                `updated_by` varchar(100) DEFAULT NULL,
                                `add_xml` text ,
                                `add_load_back` smallint(5) DEFAULT NULL,
                                `add_load_front` smallint(5) DEFAULT NULL,
                                `is_field` smallint(5) DEFAULT NULL,
                                PRIMARY KEY (`add_name`) 
                            ) " . $charset . ';';

						 $wpdb->query( $sql );

			if ( ZIGAFORM_F_LITE != 1 ) {
				$sql = "INSERT INTO $tbname VALUES ('func_anim', 'Animation effect', 'Animation effects to fields', 1, 1, NULL, NULL, NULL, 1, NULL, NULL, NULL, 1, '1980-01-01 00:00:01', '2018-01-31 10:35:14', NULL, NULL, NULL, NULL, NULL, 1, 1, 1);";
				$wpdb->query( $sql );
			}
		}
	}

			// below 3.7
	if ( version_compare( $install_ver, '3.9.5', '<' ) ) {


		$tbname = $wpdb->prefix . 'uiform_fields_type';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) == $tbname ) {

			 $sql = "INSERT INTO $tbname VALUES ('43', 'Date 2', '1', '1980-01-01 00:00:01', '2018-10-11 14:10:35', NULL, NULL, NULL, NULL) ON DUPLICATE KEY UPDATE flag_status = 1;";
			 $wpdb->query( $sql );

		}
	}

			  // below 3.9.9.6.1
	if ( version_compare( $install_ver, '3.9.9.6.1', '<' ) ) {
				  $tbname = $wpdb->prefix . 'uiform_form';

		if ( (string) $wpdb->get_var( "SHOW TABLES LIKE '$tbname'" ) === $tbname ) {

			$row = $wpdb->get_var( 'SHOW COLUMNS FROM ' . $tbname . " LIKE 'fmb_rec_tpl_html'" );
			if ( empty( $row ) ) {
						   $sql = 'ALTER TABLE ' . $tbname . ' ADD  fmb_rec_tpl_html longtext NULL;';
						   $wpdb->query( $sql );
			}

			$row = $wpdb->get_var( 'SHOW COLUMNS FROM ' . $tbname . " LIKE 'fmb_rec_tpl_st'" );
			if ( empty( $row ) ) {
				  $sql = 'ALTER TABLE ' . $tbname . ' ADD  fmb_rec_tpl_st TINYINT(1) NULL DEFAULT 0;';
				  $wpdb->query( $sql );
			}
		}
	}

	 update_option( 'uifmfbuild_version', $version );
}

