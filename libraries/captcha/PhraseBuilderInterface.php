<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
if ( interface_exists( 'PhraseBuilderInterface' ) ) {
	return;
}
/**
 * Interface for the PhraseBuilder
 *
 * @author Gregwar <g.passault@gmail.com>
 */
interface PhraseBuilderInterface {

	/**
	 * Generates  random phrase of given length with given charset
	 */
	public function build( $length, $charset);

	/**
	 * "Niceize" a code
	 */
	public function niceize( $str);
}
