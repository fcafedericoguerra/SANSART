<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	global $post, $product;
	$background_image = '';

	$tsize = 'shop_catalog';
if ( ! empty( $settings['layout'] ) && $settings['layout'] == 'carousel' && ! empty( $settings['featured_image_type'] ) ) {
	if ( $settings['featured_image_type'] == 'full' ) {
		$tsize = 'full';
	} elseif ( $settings['featured_image_type'] == 'grid' ) {
		$tsize = 'tp-image-grid';
	} elseif ( $settings['featured_image_type'] == 'custom' ) {
		$tsize = $settings['thumbnail_car_size'];
	}
}

	$image_html = tp_get_image_rander( get_the_ID(), $tsize, array(), 'post' );


	$product_id = get_the_ID();
	$data_attr  = $lazyclass = '';
if ( $layout == 'metro' ) {
	if ( ( ! empty( $display_thumbnail ) && $display_thumbnail == 'yes' ) && ! empty( $thumbnail ) ) {
		if ( has_post_thumbnail() ) {
			$src       = get_the_post_thumbnail_url( $product_id, $thumbnail );
			$data_attr = 'style="background:url(' . $src . ') #f7f7f7;"';
		} else {
			$data_attr = theplus_loading_image_grid( $product_id, 'background' );
		}
	} elseif ( has_post_thumbnail() ) {
			$data_attr = theplus_loading_bg_image( $product_id );
	} else {
		$data_attr = theplus_loading_image_grid( $product_id, 'background' );
	}

	if ( tp_has_lazyload() ) {
		$lazyclass = ' lazy-background';
	}
}
	$catalog_mode = '';
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="product-list-content">
		<?php
			/** Wishlist remove*/
		if ( ! empty( $filter_type ) && 'wishlist' === $filter_type ) {
			?>
				<div class="tp-pro-wl-remove-item" data-product="<?php echo esc_attr( get_the_ID() ); ?>">
					<i aria-hidden="true" class="fas fa-window-close"></i>
				</div>
			<?php
		}
		?>
		<?php if ( $layout != 'metro' ) { ?>
			<div class="product-content-image">
			<?php
				$out_of_stock_val = '';
			if ( ! empty( $out_of_stock ) ) {
				$out_of_stock_val = $out_of_stock;
			}
			if ( ! empty( $b_dis_badge_switch ) && $b_dis_badge_switch == 'yes' ) {
				do_action( 'theplus_product_badge', $out_of_stock_val );
			}
				$attachment_ids = $product->get_gallery_image_ids();
			if ( $attachment_ids ) {
				if ( $layout != 'metro' ) {
					?>
						<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php the_title_attribute( array( 'echo' => 0 ) ); ?>" class="product-image">
						<?php
						if ( ! get_post_meta( $attachment_ids[0], '_woocommerce_exclude_image', true ) ) {
							if ( ! empty( $hover_image_on_off ) && $hover_image_on_off == 'yes' ) {
								?>
								<span class="product-image hover-image"><?php echo tp_get_image_rander( $attachment_ids[0], $tsize ); ?></span>
								<?php
							}
						}
						?>
						<?php echo $image_html; ?>
						</a>
					<?php
				}
			} elseif ( $layout != 'metro' ) {
				if ( has_post_thumbnail() ) {
					?>
							<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="product-image" title="<?php the_title_attribute( array( 'echo' => 0 ) ); ?>">
							<?php include THEPLUS_WSTYLES . 'product/format-image.php'; ?>
							</a>
					<?php } else { ?>
							<div class="product-image">
								<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="product-image" title="<?php the_title_attribute( array( 'echo' => 0 ) ); ?>"><?php echo theplus_loading_image_grid( $product_id ); ?></a>
							</div>
						<?php
					}
			}
			?>
					
				<?php if ( $layout != 'metro' && ( ! empty( $display_cart_button ) && $display_cart_button == 'yes' ) ) { ?>
					<div class="wrapper-cart-hover-hidden add-cart-btn">
						<?php
						$_product = wc_get_product( $product_id );
						if ( $_product->is_type( 'simple' ) ) {
							?>
							<div class="product-add-to-cart" ><a title="<?php echo esc_attr__( $dcb_single_product ); ?>" href="?add-to-cart=<?php echo esc_attr( $product_id ); ?>" rel="nofollow" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="" class="add_to_cart add_to_cart_button product_type_simple ajax_add_to_cart"><span class="text"><span><?php echo esc_html__( $dcb_single_product ); ?></span></span><span class="icon"><span class="sr-loader-icon"></span><span class="check"></span></span></a></div>
						<?php } else { ?>
							<div class="product-add-to-cart" ><a rel="nofollow" href="<?php echo esc_url( get_the_permalink() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="" class="add_to_cart add_to_cart_button product_type_simple " data-added-text=""><span class="text"><span><?php echo esc_html__( $dcb_variation_product ); ?></span></span><span class="icon"><span class="sr-loader-icon"></span><span class="check"></span></span></a></div>
						<?php } ?>
					</div>
					<?php
				}

				/*yith*/
				if ( ( ! empty( $display_yith_list ) && $display_yith_list == 'yes' ) || ( ! empty( $display_theplus_quickview ) && $display_theplus_quickview == 'yes' ) ) {
					if ( $layout != 'metro' ) {
						?>
						<div class="tp-yith-wrapper">
						<?php
							/*yith compare start*/
						if ( ! empty( $display_yith_compare ) && $display_yith_compare == 'yes' ) {
							if ( defined( 'YITH_WOOCOMPARE_VERSION' ) && is_plugin_active( 'yith-woocommerce-compare/init.php' ) ) {
								?>
									<div class="tp-yith-inner post-yith-wc-compare">							
												<a href="<?php echo home_url(); ?>?action=yith-woocompare-add-product&id=<?php echo $product_id; ?>" class="compare button" data-product_id="<?php echo $product_id; ?>" rel="nofollow"><i aria-hidden="true" class="fas fa-exchange-alt"></i></a>
									</div>
								<?php
							}
						}
							/*yith compare end*/

							/*yith add to wishlist start*/
						if ( ! empty( $display_yith_wishlist ) && $display_yith_wishlist == 'yes' ) {
							if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
								?>
										<div class="tp-yith-inner  post-yith-wc-wishlist">
											<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist icon="fas fa-heart" label="" already_in_wishslist_text="" browse_wishlist_text=""]' ); ?>
										</div>
								<?php
							}
						}
							/*yith add to wishlist end*/

							/*yith quickview start*/
						if ( ! empty( $display_yith_quickview ) && $display_yith_quickview == 'yes' ) {
							if ( ( defined( 'YITH_WCQV_VERSION' ) && is_plugin_active( 'yith-woocommerce-quick-view/init.php' ) ) || ( defined( 'YITH_WCQV_PREMIUM' ) && is_plugin_active( 'yith-woocommerce-quick-view-premium/init.php' ) ) ) {
								?>
									<div class="tp-yith-inner post-yith-wc-quickview" style="opacity:0;">
											<?php echo do_shortcode( '[yith_quick_view product_id=$product_id icons="fas fa-eye"]' ); ?>
									</div>
								<?php
							}
						}
							/*yith quickview end*/

							/*tp quick view start*/
						if ( ! empty( $display_theplus_quickview ) && $display_theplus_quickview == 'yes' ) {
							?>
															
								<div class="tp-yith-inner post-yith-tp-quickview" style="opacity: 1;">
									<a href="#" class="tp-quick-view-wrap" data-product-id="<?php the_ID(); ?>"><i aria-hidden="true" class="fas fa-eye"></i></a>
								</div> 
								<?php
						}
							/*tp quick view end*/

						?>
						</div>
						<?php
					}
				}
				/*yith*/
				?>
			</div>
		<?php } ?>
		
		<div class="post-content-bottom">
			<?php
			if ( ! empty( $display_catagory ) && $display_catagory == 'yes' ) {
				include THEPLUS_WSTYLES . 'product/post-meta-catagory.php';
			}

			require THEPLUS_WSTYLES . 'product/post-meta-title.php';

			if ( ! empty( $display_rating ) && $display_rating == 'yes' ) {
				include THEPLUS_WSTYLES . 'product/post-rating.php';
			}

			if ( $product->is_in_stock() ) {
				include THEPLUS_WSTYLES . 'product/product-price.php';
			} elseif ( ! empty( $b_outofstock_price ) && $b_outofstock_price == 'yes' ) {
				include THEPLUS_WSTYLES . 'product/product-price.php';
			}

			if ( $layout == 'metro' && ( ! empty( $display_cart_button ) && $display_cart_button == 'yes' ) && $product->is_in_stock() ) {
				?>
				<div class="wrapper-cart-hover-hidden add-cart-btn">
					<?php
					$_product = wc_get_product( $product_id );
					if ( $_product->is_type( 'simple' ) ) {
						?>
						<div class="product-add-to-cart" ><a title="<?php echo esc_attr__( $dcb_single_product ); ?>" href="?add-to-cart=<?php echo esc_attr( $product_id ); ?>" rel="nofollow" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="" class="add_to_cart add_to_cart_button product_type_simple ajax_add_to_cart"><span class="text"><span><?php echo esc_html__( $dcb_single_product ); ?></span></span><span class="icon"><span class="sr-loader-icon"></span><span class="check"></span></span></a></div>
					<?php } else { ?>
						<div class="product-add-to-cart" ><a rel="nofollow" href="<?php echo esc_url( get_the_permalink() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $product_id ); ?>" data-product_sku="" class="add_to_cart add_to_cart_button product_type_simple " data-added-text=""><span class="text"><span><?php echo esc_html__( $dcb_variation_product ); ?></span></span><span class="icon"><span class="sr-loader-icon"></span><span class="check"></span></span></a></div>
					<?php } ?>
				</div>
				<?php
			}
			/*yith*/
			if ( ( ! empty( $display_yith_list ) && $display_yith_list == 'yes' ) || ( ! empty( $display_theplus_quickview ) && $display_theplus_quickview == 'yes' ) ) {
				if ( $layout == 'metro' ) {
					?>
					<div class="tp-yith-wrapper">
					<?php
					/*yith compare start*/
					if ( ! empty( $display_yith_compare ) && $display_yith_compare == 'yes' ) {
						if ( defined( 'YITH_WOOCOMPARE_VERSION' ) && is_plugin_active( 'yith-woocommerce-compare/init.php' ) ) {
							?>
								<div class="tp-yith-inner post-yith-wc-compare">							
									<a href="<?php echo home_url(); ?>?action=yith-woocompare-add-product&id=<?php echo $product_id; ?>" class="compare button" data-product_id="<?php echo $product_id; ?>" rel="nofollow"><i aria-hidden="true" class="fas fa-exchange-alt"></i></a>
								</div>
							<?php
						}
					}
					/*yith compare end*/

					/*yith add to wishlist start*/
					if ( ! empty( $display_yith_wishlist ) && $display_yith_wishlist == 'yes' ) {
						if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
							?>
								<div class="tp-yith-inner  post-yith-wc-wishlist">
									<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist icon="fas fa-heart" label="" already_in_wishslist_text="" browse_wishlist_text=""]' ); ?>
								</div>
							<?php
						}
					}
					/*yith add to wishlist end*/

					/*yith quickview start*/
					if ( ! empty( $display_yith_quickview ) && $display_yith_quickview == 'yes' ) {
						if ( ( defined( 'YITH_WCQV_VERSION' ) && is_plugin_active( 'yith-woocommerce-quick-view/init.php' ) ) || ( defined( 'YITH_WCQV_PREMIUM' ) && is_plugin_active( 'yith-woocommerce-quick-view-premium/init.php' ) ) ) {
							?>
								<div class="tp-yith-inner post-yith-wc-quickview" style="opacity:0;">
										<?php echo do_shortcode( '[yith_quick_view product_id=$product_id icons="fas fa-eye"]' ); ?>
								</div>
							<?php
						}
					}
					/*yith quickview end*/

					/*tp quick view start*/
					if ( ! empty( $display_theplus_quickview ) && $display_theplus_quickview == 'yes' ) {
						?>
							<div class="tp-yith-inner post-yith-tp-quickview" style="opacity: 1;">
								<a href="#" class="tp-quick-view-wrap" data-product-id="<?php the_ID(); ?>"><i aria-hidden="true" class="fas fa-eye"></i></a>
							</div> 
						<?php
					}
					/*tp quick view end*/

					?>
					</div>
					<?php
				}
			}
			/*yith*/
			?>
		</div>
		
		<?php if ( $layout == 'metro' ) { ?>
			<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="product-image" title="<?php the_title_attribute( array( 'echo' => 0 ) ); ?>">
				<div class="product-bg-image-metro <?php echo esc_attr( $lazyclass ); ?>" <?php echo $data_attr; ?>>
				<?php
				$out_of_stock_val = '';
				if ( ! empty( $out_of_stock ) ) {
					$out_of_stock_val = $out_of_stock;
				}
				if ( ! empty( $b_dis_badge_switch ) && $b_dis_badge_switch == 'yes' ) {
					do_action( 'theplus_product_badge', $out_of_stock_val );
				}
				?>
				</div>
			</a>
			<?php
		}
			require THEPLUS_WSTYLES . 'dynamic-listing/blog-skeleton.php';
		?>
	</div>
</article>
