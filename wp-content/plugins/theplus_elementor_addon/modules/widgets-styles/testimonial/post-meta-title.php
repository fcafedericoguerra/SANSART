<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( 'tlcontent' === $tlContentFrom ) {
	$testimonial_title = get_post_meta( get_the_id(), 'theplus_testimonial_title', true );
} elseif ( 'tlrepeater' === $tlContentFrom ) {
	$testimonial_title = wp_kses_post( $testiTitle );
}

if ( empty( $post_title_tag ) ) {
	$post_title_tag = 'h3';
}

if ( ! empty( $testimonial_title ) ) {
	if ( 'tlrepeater' === $tlContentFrom ) {
		$title = '';

		if ( $layout != 'carousel' ) {
			?> <<?php echo theplus_validate_html_tag( $post_title_tag ); ?> class="testimonial-author-title title-scroll-<?php echo esc_attr( $cntscrollOn ); ?>"><?php echo esc_html( $testimonial_title ); ?></<?php echo theplus_validate_html_tag( $post_title_tag ); ?>> <?php
		} elseif ( $titleByLimit == 'words' ) {
			$titotal           = explode( ' ', $testimonial_title );
			$tilimit_words     = explode( ' ', $testimonial_title, $titleLimit );
			$tiltn             = count( $tilimit_words );
			$tiremaining_words = implode( ' ', array_slice( $titotal, $titleLimit - 1 ) );

			if ( count( $tilimit_words ) >= $titleLimit ) {
				array_pop( $tilimit_words );
				$title = implode( ' ', $tilimit_words ) . ' <span class="testi-more-text" style = "display: none" >' . wp_kses_post( $tiremaining_words ) . '</span><a ' . $attr . ' class="testi-readbtn"> ' . esc_attr( $redmorTxt ) . ' </a>';
			} else {
				$title = implode( ' ', $tilimit_words );
			}
		} elseif ( $titleByLimit == 'letters' ) {
			$tiltn             = strlen( $testimonial_title );
			$tilimit_words     = substr( $testimonial_title, 0, $titleLimit );

			$tiremaining_words = substr( $testimonial_title, $titleLimit, $tiltn );

			if ( strlen( $testimonial_title ) > $titleLimit ) {
				$title = $tilimit_words . '<span class="testi-more-text" style = "display:none" >' . wp_kses_post( $tiremaining_words ) . '</span><a ' . $attr . ' class="testi-readbtn"> ' . esc_attr( $redmorTxt ) . ' </a>';
			} else {
				$title = $tilimit_words;
			}

		} else {
			$title = $testimonial_title;
		}
	}

	if ( 'tlcontent' === $tlContentFrom ) { ?>
		<<?php echo theplus_validate_html_tag( $post_title_tag ); ?> class="testimonial-author-title"><?php echo esc_html( $testimonial_title ); ?></<?php echo theplus_validate_html_tag( $post_title_tag ); ?>> <?php
	} elseif ( 'tlrepeater' === $tlContentFrom ) { ?>
		<<?php echo theplus_validate_html_tag( $post_title_tag ); ?> class="testimonial-author-title"><?php echo $title; ?></<?php echo theplus_validate_html_tag( $post_title_tag ); ?>> <?php
	}
} ?>
