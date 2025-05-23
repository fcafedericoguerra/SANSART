<?php
/**
 * Social Review Showmore
 *
 * @link       https://posimyth.com/
 * @package    ThePlus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$url = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';

if ( ! empty( $txtLimt ) ) {
	$ltn = '';

	if ( 'char' === $TextType ) {
		$ltn       = strlen( $Massage );
		$firstdesc = substr( $Massage, 0, $TextCount );
		$totaldesc = substr( $Massage, $TextCount, $ltn );
	} elseif ( 'word' === $TextType ) {
		$words     = explode( ' ', $Massage );
		$ltn       = count( $words );
		$firstdesc = implode( ' ', array_splice( $words, 0, $TextCount ) );
		$totaldesc = implode( ' ', array_splice( $words, 0 ) );
	}

	echo '<div class="tp-message"><div class="showtext">' . wp_kses_post( $firstdesc );

	if ( $ltn > strlen( $firstdesc ) && 'char' === $TextType || ( 'word' === $TextType && ( $ltn > count( explode( ' ', $firstdesc ) ) ) ) ) {
		echo '<span class="sf-dots">' . esc_html( $TextDots ) . '</span><div class="moreText" >' . wp_kses_post( $totaldesc ) . '</div><a class="readbtn">' . esc_html( $TextMore ) . '</a>';
	}

	echo '</div></div>';
} else {
	echo "<div class='tp-message'><div class='showtext'>" . wp_kses_post( $Massage ) . '</div></div>';
}
