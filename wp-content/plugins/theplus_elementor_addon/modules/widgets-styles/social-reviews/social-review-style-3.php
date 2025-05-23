<?php
/**
 * Social review style 2
 *
 * @link       https://posimyth.com/
 * @package    ThePlus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="grid-item <?php echo esc_attr( $desktop_class ) . ' ' . esc_attr( $tablet_class ) . ' ' . esc_attr( $mobile_class ) . ' ' . esc_attr( $category_filter ) . ' ' . esc_attr( $r_key ) . ' ' . esc_attr( $ReviewClass ); ?>">
	<?php
		require THEPLUS_WSTYLES . 'social-reviews/social-review-ob-style.php';
		echo '<div class="tp-review ' . esc_attr( $ErrClass ) . '">';
			echo $Star_HTML;
			echo $Logo_HTML;
			echo $Description_HTML;
		echo '</div>';
		echo $Copyid_html;
		echo '<div class="tp-SR-header">';
			echo $Profile_HTML;
			echo '<div class="tp-SR-separator">';
				echo $UserName_HTML;
				echo $Time_HTML;
			echo '</div>';
		echo '</div>';
	?>
</div>
