<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $post;
	$postid             = get_the_ID();
	$featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
	$tsize              = '';
if ( ! empty( $featured_image_url ) ) {
	if ( ! empty( $layout ) && ( $layout == 'grid' || $layout == 'masonry' ) ) {
		if ( ( ! empty( $display_thumbnail ) && $display_thumbnail == 'yes' ) && ! empty( $thumbnail ) ) {
			$tsize = $thumbnail;
		} else {
			$tsize = 'tp-image-grid';
		}
		$featured_image = tp_get_image_rander( get_the_ID(), $tsize, array(), 'post' );
	} elseif ( ! empty( $layout ) && $layout == 'carousel' ) {
		if ( empty( $featured_image_type ) ) {
			$featured_image_type = 'full';
		} elseif ( $featured_image_type == 'grid' ) {
				$featured_image_type = 'tp-image-grid';
		} elseif ( $featured_image_type == 'custom' ) {
			$featured_image_type = $thumbnail_car;
		}
		$featured_image = tp_get_image_rander( get_the_ID(), $featured_image_type, array(), 'post' );
	} else {
		$featured_image = tp_get_image_rander( get_the_ID(), 'full', array(), 'post' );
	}
} else {
	$featured_image = theplus_get_thumb_url();
	$featured_image = $featured_image = '<img src="' . esc_url( $featured_image ) . '" alt="' . esc_attr( get_the_title() ) . '">';
}
?>
	<div class="product-featured-image">
	<span class="thumb-wrap">
		<?php echo $featured_image; ?>
	</span>
	</div>
