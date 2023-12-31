<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage RAIDERSPIRIT
 * @since RAIDERSPIRIT 1.0
 */

$raiderspirit_header_css = '';
$raiderspirit_header_image = get_header_image();
$raiderspirit_header_video = raiderspirit_get_header_video();
if (!empty($raiderspirit_header_image) && raiderspirit_trx_addons_featured_image_override(is_singular() || raiderspirit_storage_isset('blog_archive') || is_category())) {
	$raiderspirit_header_image = raiderspirit_get_current_mode_image($raiderspirit_header_image);
}

?><header class="top_panel top_panel_default<?php
					echo !empty($raiderspirit_header_image) || !empty($raiderspirit_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($raiderspirit_header_video!='') echo ' with_bg_video';
					if ($raiderspirit_header_image!='') echo ' '.esc_attr(raiderspirit_add_inline_css_class('background-image: url('.esc_url($raiderspirit_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (raiderspirit_is_on(raiderspirit_get_theme_option('header_fullheight'))) echo ' header_fullheight raiderspirit-full-height';
					if (!raiderspirit_is_inherit(raiderspirit_get_theme_option('header_scheme')))
						echo ' scheme_' . esc_attr(raiderspirit_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($raiderspirit_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (raiderspirit_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Mobile header
	if (raiderspirit_is_on(raiderspirit_get_theme_option("header_mobile_enabled"))) {
		get_template_part( 'templates/header-mobile' );
	}
	
	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

	// Display featured image in the header on the single posts
	// Comment next line to prevent show featured image in the header area
	// and display it in the post's content
	get_template_part( 'templates/header-single' );

?></header>