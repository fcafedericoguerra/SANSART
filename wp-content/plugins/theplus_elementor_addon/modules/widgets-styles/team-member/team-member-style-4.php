<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$postid = get_the_ID();

if ( ! empty( $layered_image_animation_effect ) && $layered_image_animation_effect != 'none' ) {
	$animated_class = $animation_class . $layered_image_animation_effect;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="team-list-content">
		<div class="post-content-image">
			<div class="bg-image-layered <?php echo esc_attr( $animated_class ); ?>"></div>
			<?php if ( empty( $disable_link ) && $disable_link != 'yes' ) { ?>
				<a rel="<?php echo esc_attr( $member_urlNofollow ); ?>" href="<?php echo esc_url( $member_url ); ?>" target="<?php echo esc_attr( $member_urlBlank ); ?>">
				<?php
			}

				require THEPLUS_WSTYLES . 'team-member/format-image.php';

			if ( empty( $disable_link ) && $disable_link != 'yes' ) {
				?>
				</a> <?php } ?>
		</div>		
		<div class="post-content-bottom">
			<?php
				require THEPLUS_WSTYLES . 'team-member/post-meta-title.php';

			if ( ! empty( $designation ) && ! empty( $display_designation ) && $display_designation == 'yes' ) {
				echo $designation;
			}

			if ( ! empty( $team_social_contnet ) && ! empty( $display_social_icon ) && $display_social_icon == 'yes' ) {
				echo $team_social_contnet;
			}
			?>
		</div>
	<?php
		require THEPLUS_WSTYLES . 'dynamic-listing/blog-skeleton.php'; 
	?>
	</div>
</article>
