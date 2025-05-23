<?php
/**
 * Meta Title
 *
 * @package ThePlus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! isset( $post_title_tag ) && empty( $post_title_tag ) ) {
	$post_title_tag = 'h3';
} ?>

<<?php echo theplus_validate_html_tag( $post_title_tag ); ?> class="post-title">
	
	<?php
	if ( ! empty( $custom_url ) ) {
		if ( ! empty( $settings['display_box_link'] ) && 'yes' === $settings['display_box_link'] ) {
			?>
			<div>
				<?php echo esc_html( $title ); ?>
			</div>
		<?php } else { ?>
			<a href="<?php echo esc_url( $custom_url ); ?>" 
				<?php
					echo $target;
					echo $nofollow;
				?>
			><?php echo esc_html( $title ); ?></a>
			<?php
		}
	} elseif ( 'no' !== $popup_style ) {
		?>
			<?php if ( ! empty( $settings['display_box_link'] ) && 'yes' === $settings['display_box_link'] ) { ?>
				<div <?php echo $popup_attr; ?>><?php echo esc_html( $title ); ?></div>
			<?php } else { ?>
				<a href="<?php echo esc_url( $full_image ); ?>" <?php echo $popup_attr; ?>><?php echo esc_html( $title ); ?></a>
			<?php } ?>
		<?php
	} else {
		echo esc_html( $title );
	}
	?>

</<?php echo theplus_validate_html_tag( $post_title_tag ); ?>>
