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
if ( class_exists( 'Uiform_Form_Helper' ) ) {
	return;
}

class Uiform_Form_Helper {


	public static function human_filesize( $bytes, $decimals = 2 ) {
		$size   = array( 'B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );
		$factor = floor( ( strlen( $bytes ) - 1 ) / 3 );
		return sprintf( "%.{$decimals}f", $bytes / pow( 1024, $factor ) ) . @$size[ $factor ];
	}

	public static function getroute() {
		 $return = array();
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			// post
			$return['module']     = isset( $_POST['zgfm_mod'] ) ? self::sanitizeInput( $_POST['zgfm_mod'] ) : '';
			$return['controller'] = isset( $_POST['zgfm_contr'] ) ? self::sanitizeInput( $_POST['zgfm_contr'] ) : '';
			$return['action']     = isset( $_POST['zgfm_action'] ) ? self::sanitizeInput( $_POST['zgfm_action'] ) : '';
		} elseif ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
			// get
			$return['module']     = isset( $_GET['zgfm_mod'] ) ? self::sanitizeInput( $_GET['zgfm_mod'] ) : '';
			$return['controller'] = isset( $_GET['zgfm_contr'] ) ? self::sanitizeInput( $_GET['zgfm_contr'] ) : '';
			$return['action']     = isset( $_GET['zgfm_action'] ) ? self::sanitizeInput( $_GET['zgfm_action'] ) : '';
		} else {
			// request
			$return['module']     = isset( $_REQUEST['zgfm_mod'] ) ? self::sanitizeInput( $_REQUEST['zgfm_mod'] ) : '';
			$return['controller'] = isset( $_REQUEST['zgfm_contr'] ) ? self::sanitizeInput( $_REQUEST['zgfm_contr'] ) : '';
			$return['action']     = isset( $_REQUEST['zgfm_action'] ) ? self::sanitizeInput( $_REQUEST['zgfm_action'] ) : '';
		}
		return $return;
	}

	public static function getHttpRequest( $var ) {
		 $var = strval( $var );
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
			// post
			$value = isset( $_POST[ $var ] ) ? self::sanitizeInput( $_POST[ $var ] ) : '';
		} elseif ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
			// get
			$value = isset( $_GET[ $var ] ) ? self::sanitizeInput( $_GET[ $var ] ) : '';
		} else {
			// request
			$value = isset( $_REQUEST[ $var ] ) ? self::sanitizeInput( $_REQUEST[ $var ] ) : '';
		}

		return $value;
	}

	public static function array2xml( $array, $xml = null ) {
		if ( ! isset( $xml ) ) {
			$xml = new SimpleXMLElement( '<params/>' );
		}
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				self::array2xml( $value, $xml );
			} else {
				if ( is_numeric( $key ) ) {
					if ( is_string( $value ) ) {
						$xml->addChild( 'item', htmlentities( $value, ENT_NOQUOTES, 'UTF-8' ) );
					} else {
						$xml->addChild( 'item', $value );
					}
				} elseif ( is_string( $value ) ) {
					$xml->addChild( $key, htmlentities( $value, ENT_NOQUOTES, 'UTF-8' ) );
				} else {
					$xml->addChild( $key, $value );
				}
			}
		}
		return $xml->asXML();
	}

	public static function generate_pagination() {
	}

	public static function convert_HexToRGB( $hex ) {
		// Format the hex color string
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}

		// Get decimal values
		$arr   = array();
		$arr[] = $r = hexdec( substr( $hex, 0, 2 ) );
		$arr[] = $g = hexdec( substr( $hex, 2, 2 ) );
		$arr[] = $b = hexdec( substr( $hex, 4, 2 ) );

		return $arr;
	}

	/**
	 * Sanitize input
	 *
	 * @param string $string input
	 *
	 * @return array
	 */
	public static function sanitizeInput( $string ) {
		$string = filter_var( $string, FILTER_SANITIZE_STRING );
		$string = stripslashes( $string );
		$string = str_replace( array( '‘', '’', '“', '”' ), array( "'", "'", '"', '"' ), $string );
		$string = html_entity_decode( $string, ENT_QUOTES, 'UTF-8' );
		$string = preg_replace( '/[\n\r\t]/', ' ', $string );
		$string = trim( $string, "\x00..\x1F" );
		$string = sanitize_text_field( $string );
		return $string;
	}

	/**
	 * Sanitize input 2
	 *
	 * @param string $string input
	 *
	 * @return array
	 */
	public static function sanitizeInput_html( $string ) {
		$string = stripslashes( $string );
		$string = str_replace( array( '‘', '’', '“', '”' ), array( "'", "'", '"', '"' ), $string );
		$string = html_entity_decode( $string, ENT_QUOTES, 'UTF-8' );
		$string = preg_replace( '/[\n\r\t]/', ' ', $string );
		$string = trim( $string, "\x00..\x1F" );
		return $string;
	}

	/**
	 * Sanitize input
	 *
	 * @param string $string input
	 *
	 * @return array
	 */
	public static function sanitizeFnamestring( $string ) {
		 $string = preg_replace( '/\s+/', '', $string );
		$string  = preg_replace( "/'/i", '', $string );
		$string  = preg_replace( '/"/i', '', $string );
		$string  = preg_replace( '/[^\pL\pN]+/', '', $string );
		$string  = preg_replace( '/[^a-zA-Z0-9]+/', '', $string );
		$string  = strtolower( $string );
		// reserved words
		switch ( trim( $string ) ) {
			case 'check':
				$string = $string . '1';
				break;
			default:
				// code...
				break;
		}

		return $string;
	}

	/**
	 * Sanitize input
	 *
	 * @param string $string input
	 *
	 * @return array
	 */
	public static function sanitizeFileName( $string ) {
		$string = preg_replace( '/\s+/', '', $string );
		$string = preg_replace( "/'/i", '', $string );
		$string = preg_replace( '/"/i', '', $string );
		$string = preg_replace( '/[^\pL\pN_-]+/', '', $string );
		$string = preg_replace( '/[^a-zA-Z0-9_-]+/', '', $string );
		$string = strtolower( $string );
		return $string;
	}

	/**
	 * Sanitize recursive
	 *
	 * @param string $data array
	 *
	 * @return array
	 */
	public static function sanitizeRecursive( $data ) {
		if ( is_array( $data ) ) {
			return array_map( array( 'Uiform_Form_Helper', 'sanitizeRecursive' ), $data );
		} else {
			return self::sanitizeInput( $data );
		}
	}

	/**
	 * Sanitize recursive
	 *
	 * @param string $data array
	 *
	 * @return array
	 */
	public static function sanitizeRecursive_html( $data ) {
		if ( is_array( $data ) ) {
			return array_map( array( 'Uiform_Form_Helper', 'sanitizeRecursive_html' ), $data );
		} else {
			return self::sanitizeInput_html( $data );
		}
	}

	public static function data_encrypt( $string, $key ) {
		$output = '';
		/*   if(function_exists("mcrypt_encrypt")) { */
		if ( 0 ) {
			$output = rtrim(
				base64_encode(
					mcrypt_encrypt(
						MCRYPT_RIJNDAEL_256,
						$key,
						$string,
						MCRYPT_MODE_ECB,
						mcrypt_create_iv(
							mcrypt_get_iv_size(
								MCRYPT_RIJNDAEL_256,
								MCRYPT_MODE_ECB
							),
							MCRYPT_RAND
						)
					)
				),
				"\0"
			);
		} else {
			$result = '';
			for ( $i = 0; $i < strlen( $string ); $i++ ) {
				$char    = substr( $string, $i, 1 );
				$keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1 );
				$char    = chr( ord( $char ) + ord( $keychar ) );
				$result .= $char;
			}
			$output = base64_encode( $result );
		}

		return $output;
	}

	public static function data_decrypt( $string, $key ) {
		$output = '';
		/* if(function_exists("mcrypt_encrypt")) { */
		if ( 0 ) {
			$output = rtrim(
				mcrypt_decrypt(
					MCRYPT_RIJNDAEL_256,
					$key,
					base64_decode( $string ),
					MCRYPT_MODE_ECB,
					mcrypt_create_iv(
						mcrypt_get_iv_size(
							MCRYPT_RIJNDAEL_256,
							MCRYPT_MODE_ECB
						),
						MCRYPT_RAND
					)
				),
				"\0"
			);
		} else {
			$result = '';
			$string = base64_decode( $string );

			for ( $i = 0; $i < strlen( $string ); $i++ ) {
				$char    = substr( $string, $i, 1 );
				$keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1 );
				$char    = chr( ord( $char ) - ord( $keychar ) );
				$result .= $char;
			}
			$output = $result;
		}

		return $output;
	}

	public static function base64url_encode( $s ) {
		 return str_replace( array( '+', '/' ), array( '-', '_' ), base64_encode( $s ) );
	}

	public static function base64url_decode( $s ) {
		 return base64_decode( str_replace( array( '-', '_' ), array( '+', '/' ), $s ) );
	}

	// Javascript/HTML hex encode
	public static function encodeHex( $input ) {
		$temp   = '';
		$length = strlen( $input );
		for ( $i = 0; $i < $length; $i++ ) {
			$temp .= '%' . bin2hex( $input[ $i ] );
		}

		return $temp;
	}

	public static function check_field_length( $data, $length ) {
		return ( strlen( $data ) > intval( $length ) ) ? substr( $data, 0, intval( $length ) ) : '';
	}

	public static function sql_quote( $value ) {
		if ( get_magic_quotes_gpc() ) {
			$value = stripslashes( $value );
		}

		$value = addslashes( $value );

		return $value;
	}

	public static function form_store_fonts( $font_temp ) {
		 global $global_fonts_stored;
		if ( ! empty( $font_temp['import_family'] ) && ! in_array( $font_temp['import_family'], $global_fonts_stored ) ) {
			$global_fonts_stored[] = $font_temp['import_family'];
		}
	}

	public static function is_uiform_page() {
		$vget_page  = ( isset( $_GET['page'] ) ) ? self::sanitizeInput( $_GET['page'] ) : '';
		$vpost_page = ( isset( $_POST['page'] ) ) ? self::sanitizeInput( $_POST['page'] ) : '';

		// $wpage=(isset($_GET['page']))?Uiform_Form_Helper::sanitizeInput($_GET['page']):'';
		if ( ( $vget_page === 'zgfm_form_builder' ) || ( $vpost_page === 'zgfm_form_builder' ) ) {
			return true;
		} else {
			return false;
		}
	}

	public static function remove_non_tag_space( $text ) {
		$len    = strlen( $text );
		$out    = '';
		$in_tag = false;
		for ( $i = 0; $i < $len; $i++ ) {
			$c = $text[ $i ];
			if ( $c == '<' ) {
				$in_tag = true;
			} elseif ( $c == '>' ) {
				$in_tag = false;
			}

			$out .= $c == ' ' ? ( $in_tag ? $c : '' ) : $c;
		}
		return $out;
	}

	public static function assign_alert_container( $msg, $type ) {
		$return_msg = '';
		switch ( $type ) {
			case 1:
				/*success*/
				$return_msg .= '<div class="alert alert-success" role="alert">' . $msg . '</div>';
				break;
			case 2:
				/*info*/
				$return_msg .= '<div class="alert alert-info" role="alert">' . $msg . '</div>';
				break;
			case 3:
				/*warning*/
				$return_msg .= '<div class="alert alert-warning" role="alert">' . $msg . '</div>';
				break;
			case 4:
				/*danger*/
				$return_msg .= '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
				break;
			default:
				break;
		}
		return $return_msg;
	}

	/**
	 * Verify if field is checked
	 *
	 * @param int $row    value field
	 * @param int $status status check
	 *
	 * @return array
	 */
	public static function getChecked( $row, $status ) {
		if ( $row == $status ) {
			echo 'checked="checked"';
		}
	}

	public static function sanitize_output( $buffer ) {
		$search = array(
			'/\>[^\S ]+/s', // strip whitespaces after tags, except space
			'/[^\S ]+\</s', // strip whitespaces before tags, except space
			'/(\s)+/s', // shorten multiple whitespace sequences
		);

		$replace = array(
			'>',
			'<',
			'\\1',
		);

		$buffer = preg_replace( $search, $replace, $buffer );

		return $buffer;
	}

	public static function getCurrency( $currency = false ) {
		$currencies = array(
			'USD' => array(
				'name'               => __( 'U.S. Dollar', 'FRocket_admin' ),
				'symbol_left'        => '$',
				'symbol_right'       => '',
				'symbol_padding'     => '',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'AUD' => array(
				'name'               => __( 'Australian Dollar', 'FRocket_admin' ),
				'symbol_left'        => '$',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'BRL' => array(
				'name'               => __( 'Brazilian Real', 'FRocket_admin' ),
				'symbol_left'        => 'R$',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => '.',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
			'CAD' => array(
				'name'               => __( 'Canadian Dollar', 'FRocket_admin' ),
				'symbol_left'        => '$',
				'symbol_right'       => 'CAD',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'CZK' => array(
				'name'               => __( 'Czech Koruna', 'FRocket_admin' ),
				'symbol_left'        => '',
				'symbol_right'       => '&#75;&#269;',
				'symbol_padding'     => ' ',
				'thousand_separator' => ' ',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
			'DKK' => array(
				'name'               => __( 'Danish Krone', 'FRocket_admin' ),
				'symbol_left'        => 'Kr',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => '.',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
			'EUR' => array(
				'name'               => __( 'Euro', 'FRocket_admin' ),
				'symbol_left'        => '',
				'symbol_right'       => '&#8364;',
				'symbol_padding'     => ' ',
				'thousand_separator' => '.',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
			'HKD' => array(
				'name'               => __( 'Hong Kong Dollar', 'FRocket_admin' ),
				'symbol_left'        => 'HK$',
				'symbol_right'       => '',
				'symbol_padding'     => '',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'HUF' => array(
				'name'               => __( 'Hungarian Forint', 'FRocket_admin' ),
				'symbol_left'        => '',
				'symbol_right'       => 'Ft',
				'symbol_padding'     => ' ',
				'thousand_separator' => '.',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
			'ILS' => array(
				'name'               => __( 'Israeli New Sheqel', 'FRocket_admin' ),
				'symbol_left'        => '&#8362;',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'JPY' => array(
				'name'               => __( 'Japanese Yen', 'FRocket_admin' ),
				'symbol_left'        => '&#165;',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '',
				'decimals'           => 0,
			),
			'MYR' => array(
				'name'               => __( 'Malaysian Ringgit', 'FRocket_admin' ),
				'symbol_left'        => '&#82;&#77;',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'MXN' => array(
				'name'               => __( 'Mexican Peso', 'FRocket_admin' ),
				'symbol_left'        => '$',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'NOK' => array(
				'name'               => __( 'Norwegian Krone', 'FRocket_admin' ),
				'symbol_left'        => 'Kr',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => '.',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
			'NZD' => array(
				'name'               => __( 'New Zealand Dollar', 'FRocket_admin' ),
				'symbol_left'        => '$',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'PHP' => array(
				'name'               => __( 'Philippine Peso', 'FRocket_admin' ),
				'symbol_left'        => 'Php',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'PLN' => array(
				'name'               => __( 'Polish Zloty', 'FRocket_admin' ),
				'symbol_left'        => '&#122;&#322;',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => '.',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
			'GBP' => array(
				'name'               => __( 'Pound Sterling', 'FRocket_admin' ),
				'symbol_left'        => '&#163;',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'SGD' => array(
				'name'               => __( 'Singapore Dollar', 'FRocket_admin' ),
				'symbol_left'        => '$',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'SEK' => array(
				'name'               => __( 'Swedish Krona', 'FRocket_admin' ),
				'symbol_left'        => '',
				'symbol_right'       => 'Kr',
				'symbol_padding'     => ' ',
				'thousand_separator' => ' ',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
			'CHF' => array(
				'name'               => __( 'Swiss Franc', 'FRocket_admin' ),
				'symbol_left'        => 'Fr.',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => "'",
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'TWD' => array(
				'name'               => __( 'Taiwan New Dollar', 'FRocket_admin' ),
				'symbol_left'        => '$',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'THB' => array(
				'name'               => __( 'Thai Baht', 'FRocket_admin' ),
				'symbol_left'        => '&#3647;',
				'symbol_right'       => '',
				'symbol_padding'     => ' ',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'decimals'           => 2,
			),
			'TRY' => array(
				'name'               => __( 'Turkish Liras', 'FRocket_admin' ),
				'symbol_left'        => '',
				'symbol_right'       => '&#8364;',
				'symbol_padding'     => ' ',
				'thousand_separator' => '.',
				'decimal_separator'  => ',',
				'decimals'           => 2,
			),
		);

		if ( $currency && isset( $currencies[ $currency ] ) ) {
			return $currencies[ $currency ];
		}

		return $currencies;
	}

	/**
	 * Escape String
	 *
	 * @access    public
	 * @param    string
	 * @param    bool    whether or not the string will be used in a LIKE condition
	 * @return    string
	 */
	public static function escape_str( $str, $like = false ) {
		if ( is_array( $str ) ) {
			foreach ( $str as $key => $val ) {
				$str[ $key ] = $this->escape_str( $val, $like );
			}

			return $str;
		}

		if ( ! version_compare( '5.5', phpversion(), '>=' ) ) {
			$str = addslashes( $str );
		} else {
			if ( function_exists( 'mysql_real_escape_string' ) ) {
				$str = mysql_real_escape_string( $str );
			} elseif ( function_exists( 'mysql_escape_string' ) ) {
				$str = mysql_escape_string( $str );
			} else {
				$str = addslashes( $str );
			}
		}

		return $str;
	}

	public static function mysql_version() {
		if ( ! version_compare( '5.5', phpversion(), '>=' ) ) {

			$database_name     = DB_NAME;
			$database_user     = DB_USER;
			$datadase_password = DB_PASSWORD;
			$database_host     = DB_HOST;

			$con = mysqli_connect( $database_host, $database_user, $datadase_password, $database_name );
			// Check connection
			if ( mysqli_connect_errno() ) {
				// echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}

			$str = mysqli_get_server_info( $con );
		} else {
			$str = mysql_get_server_info();
		}

		return $str;
	}

	public static function isValidUrl_structure( $url ) {
		// first do some quick sanity checks:
		if ( ! $url || ! is_string( $url ) ) {
			return false;
		}
		// quick check url is roughly a valid http request: ( http://blah/... )
		if ( ! preg_match( '/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url ) ) {
			return false;
		}

		// all good!
		return true;
	}

	public static function json_encode_advanced( array $arr, $sequential_keys = false, $quotes = false, $beautiful_json = false ) {
		$output = self::isAssoc( $arr ) ? '{' : '[';
		$count  = 0;
		foreach ( $arr as $key => $value ) {

			if ( self::isAssoc( $arr ) || ( ! self::isAssoc( $arr ) && $sequential_keys == true ) ) {
				$output .= ( $quotes ? '"' : '' ) . $key . ( $quotes ? '"' : '' ) . ' : ';
			}

			if ( is_array( $value ) ) {
				$output .= self::json_encode_advanced( $value, $sequential_keys, $quotes, $beautiful_json );
			} elseif ( is_bool( $value ) ) {
				$output .= ( $value ? 'true' : 'false' );
			} elseif ( is_numeric( $value ) ) {
				$output .= $value;
			} elseif ( is_object( $value ) ) {
				$output .= '0';
			} else {
				$output .= ( $quotes || $beautiful_json ? '"' : '' ) . $value . ( $quotes || $beautiful_json ? '"' : '' );
			}

			if ( ++$count < count( $arr ) ) {
				$output .= ', ';
			}
		}

		$output .= self::isAssoc( $arr ) ? '}' : ']';

		return $output;
	}

	public static function isAssoc( array $arr ) {
		if ( array() === $arr ) {
			return false;
		}

		return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
	}

	public static function zigaform_user_is_on_admin_page( $page_name = 'admin.php' ) {
		 global $pagenow;
		return ( $pagenow == $page_name );
	}

	public static function get_font_library() {
		 require_once UIFORM_FORMS_DIR . '/libraries/styles-font-menu/plugin.php';
		$objsfm = new SFM_Plugin();

		return $objsfm;
	}

	public static function check_User_Access() {
		$form_id = null;
		// for logged users
		if ( ! is_user_logged_in() ) {
			return false;
		}

		// check form id

		// make sure the user have manage options
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		return true;
	}

	public static function raw_json_encode( $input, $flags = 0 ) {
		$fails    = implode(
			'|',
			array_filter(
				array(
					'\\\\',
					$flags & JSON_HEX_TAG ? 'u003[CE]' : '',
					$flags & JSON_HEX_AMP ? 'u0026' : '',
					$flags & JSON_HEX_APOS ? 'u0027' : '',
					$flags & JSON_HEX_QUOT ? 'u0022' : '',
				)
			)
		);
		$pattern  = "/\\\\(?:(?:$fails)(*SKIP)(*FAIL)|u([0-9a-fA-F]{4}))/";
		$callback = function ( $m ) {
			return html_entity_decode( "&#x$m[1];", ENT_QUOTES, 'UTF-8' );
		};
		return preg_replace_callback( $pattern, $callback, json_encode( $input, $flags ) );
	}

	/*
	 * get label block position of grid system
	 */
	public static function field_label_blockpos_gridsys( $pos ) {
		$output = array();
		switch ( intval( $pos ) ) {
			case 1:
				$output['left']  = 1;
				$output['right'] = 11;
				break;
			case 3:
				$output['left']  = 3;
				$output['right'] = 9;
				break;
			case 4:
				$output['left']  = 4;
				$output['right'] = 8;
				break;
			case 5:
				$output['left']  = 5;
				$output['right'] = 7;
				break;
			case 6:
				$output['left']  = 6;
				$output['right'] = 6;
				break;
			case 7:
				$output['left']  = 7;
				$output['right'] = 5;
				break;
			case 8:
				$output['left']  = 8;
				$output['right'] = 4;
				break;
			case 9:
				$output['left']  = 9;
				$output['right'] = 3;
				break;
			case 10:
				$output['left']  = 10;
				$output['right'] = 2;
				break;
			case 11:
				$output['left']  = 11;
				$output['right'] = 1;
				break;
			case 2:
			default:
				$output['left']  = 2;
				$output['right'] = 10;
				break;
		}

		return $output;
	}

	/**
	 * php encodeURIComponent.
	 *
	 * @author    Unknown
	 * @since    v0.0.1
	 * @version    v1.0.0    Saturday, April 11th, 2020.
	 * @access    public static
	 * @param    mixed $str
	 * @return    mixed
	 */
	public static function encodeURIComponent( $str ) {
		 $revert = array(
			 '%21' => '!',
			 '%2A' => '*',
			 '%27' => "'",
			 '%28' => '(',
			 '%29' => ')',
		 );
		 return strtr( rawurlencode( $str ), $revert );
	}

}

use Dompdf\Dompdf;

function uifm_generate_pdf( $html, $filename, $papersize, $paperorien, $stream = true ) {
	if ( ZIGAFORM_F_LITE == 1 ) {

	} else {
		
		if (version_compare(phpversion(), '7.1', '>='))
		{
			require_once UIFORM_FORMS_DIR . '/helpers/dompdf/0.8.5/autoload.inc.php';
		}else{
			require_once UIFORM_FORMS_DIR . '/helpers/dompdf/0.8.3/autoload.inc.php';
		}

		
		$dompdf = new Dompdf();

		$dompdf->loadHtml( $html );
		$dompdf->setPaper( $papersize, $paperorien );
		$dompdf->set_option( 'isHtml5ParserEnabled', true );
		$dompdf->set_option( 'isRemoteEnabled', true );
		$dompdf->render();

		if ( $stream ) {

			$dompdf->stream( $filename );
		} else {
			$output = $dompdf->output();

			$filename     = Uiform_Form_Helper::sanitizeFileName( $filename );
			$file_to_save = UIFORM_FORMS_DIR . '/temp/' . $filename . '.pdf';
			file_put_contents( $file_to_save, $output );
			$output = UIFORM_FORMS_DIR . '/temp/' . $filename . '.pdf';
			return $output;
		}
	}

}
