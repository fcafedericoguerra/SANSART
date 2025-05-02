<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$postid = get_the_ID();

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="team-list-content">
		<div class="post-content-image">
			<?php if ( empty( $disable_link ) && $disable_link != 'yes' ) { ?>
				<a rel="<?php echo esc_attr( $member_urlNofollow ); ?>" href="<?php echo esc_url( $member_url ); ?>" target="<?php echo esc_attr( $member_urlBlank ); ?>">
				<?php
			}

				require THEPLUS_WSTYLES . 'team-member/format-image.php';

			if ( empty( $disable_link ) && $disable_link != 'yes' ) {
				?>
				</a>

			<?php } ?>
		</div>		
		<div class="post-content-bottom">			
			<?php
				require THEPLUS_WSTYLES . 'team-member/post-meta-title.php';

			if ( ! empty( $designation ) && ! empty( $display_designation ) && $display_designation == 'yes' ) {
				echo $designation;
			}
			?>
		</div>
	<?php
		require THEPLUS_WSTYLES . 'dynamic-listing/blog-skeleton.php'; 
	?>
	</div>
</article>
