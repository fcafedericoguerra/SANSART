<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$postid  = get_the_ID();
$bg_attr = $lazyclass = '';
if ( ( ! empty( $display_thumbnail ) && $display_thumbnail == 'yes' ) && ! empty( $thumbnail ) ) {
	$featured_image = get_the_post_thumbnail_url( $postid, $thumbnail );
	if ( ! empty( $featured_image ) ) {
		$bg_attr = 'style="background:url(' . $featured_image . ') #f7f7f7;"';
	} else {
		$bg_attr = theplus_loading_image_grid( $postid, 'background' );
	}
} else {
	$featured_image = get_the_post_thumbnail_url( $postid, 'full' );
	if ( ! empty( $featured_image ) ) {
		$bg_attr = theplus_loading_bg_image( $postid );
	} else {
		$bg_attr = theplus_loading_image_grid( $postid, 'background' );
	}
}

if ( tp_has_lazyload() ) {
	$lazyclass = ' lazy-background';
}
?>

<div class="bss-wrapper">
			<div class="post-content-image-bg <?php echo esc_attr( $lazyclass ); ?>" <?php echo $bg_attr; ?> >
				<div class="post-content-wrapper">					
						<?php if ( $display_post_category == 'yes' ) { ?>
							<?php include THEPLUS_WSTYLES . 'dynamic-smart-showcase/blog-category-' . $post_category_style . '.php'; ?>
						<?php } ?>	
						<div class="bss-content">
								<?php
								require THEPLUS_WSTYLES . 'dynamic-smart-showcase/post-meta-title.php';
								if ( ! empty( $display_excerpt ) && $display_excerpt == 'yes' && get_the_excerpt() ) {
									?>
									<?php include THEPLUS_WSTYLES . 'dynamic-smart-showcase/get-excerpt.php'; ?>									
								<?php } ?>	
								<div class="bss-meta-content"><a href="<?php echo esc_url( get_the_permalink() ); ?>" class="bss-meta-content-link">
									<?php if ( ! empty( $display_post_meta ) && $display_post_meta == 'yes' ) { ?>
											<?php include THEPLUS_WSTYLES . 'dynamic-smart-showcase/blog-post-meta-new-' . $post_meta_tag_style . '.php'; ?>
									<?php } ?></a>																	
								</div>						
					</div>
				</div>
			</div>
			<div class="post-content-remain-list">	
				<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="bss-remain-img">
					<?php require THEPLUS_WSTYLES . 'dynamic-smart-showcase/format-image.php'; ?>
				</a>
				<div class="bss-content">				
						<div class="bss-meta-content"><a href="<?php echo esc_url( get_the_permalink() ); ?>" class="bss-meta-content-link">
							<?php if ( ! empty( $display_post_meta ) && $display_post_meta == 'yes' ) { ?>
									<?php include THEPLUS_WSTYLES . 'dynamic-smart-showcase/blog-post-meta-' . $post_meta_tag_style . '.php'; ?>
							<?php } ?></a>
						</div>
						<?php
						require THEPLUS_WSTYLES . 'dynamic-smart-showcase/post-meta-title.php';
						if ( ! empty( $display_excerpt ) && $display_excerpt == 'yes' && get_the_excerpt() ) {
							?>
									<?php include THEPLUS_WSTYLES . 'dynamic-smart-showcase/get-excerpt.php'; ?>									
								<?php } ?>																	
				</div>
			</div>		
</div>
