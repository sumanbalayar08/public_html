<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Expatsport
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!-- VIEWPORT & APP -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-tap-highlight" content="no" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<script type="text/javascript">
	    var baseUrl = '<?php echo site_url(); ?>';
	    var tempUrl = '<?php echo get_template_directory_uri(); ?>';
	    var pageUrl = '<?php echo get_permalink(); ?>';
	</script>

	<?php wp_body_open(); ?>

	<div id="page_content">
			<header class="custom-px">
				<div class="row gx-0 align-items-center justify-content-end">
					<div class="col-6 col-md-2">
						<?php $headerLogo = get_field('header_logo', 'options'); ?>
						<div class="logo">
							<div class="img-w">
								<a href="<?php echo site_url(); ?>">
									<?php echo get_custom_image($headerLogo['image_part']['ID'], false, $class = '','header Logo'); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="col-6 col-md-10 d-flex align-items-center justify-content-end">
						<div class="menu">
							<?php if(has_nav_menu( 'primary' )): ?>
							<?php
								wp_nav_menu(
									array(
										'theme_location' => 'primary',
										'menu_class' => 'menu d-md-flex',
										'menu_item_class' => '',
										'menu_item_link_class' => '',
										'fallback_cb'    => false,
									)
								);
							?>
							<?php endif; ?>
						</div>
						<div class="menu-icon image-responsive d-flex p-relative">
							<a class="menu__link img-w">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="50px" height="50px">
								  <path class="line line1" d="M 0 7.5 L 50 7.5" />
								  <path class="line line2" d="M 0 22.5 L 50 22.5"/>
								  <path class="line line3" d="M 0 37.5 L 50 37.5" />
								  <!-- Hover lines -->
								  <path class="draw-line draw-line1" d="M 0 7.5 L 50 7.5" />
								  <path class="draw-line draw-line2" d="M 0 22.5 L 50 22.5" />
								  <path class="draw-line draw-line3" d="M 0 37.5 L 50 37.5" />
								</svg>
							</a>
						</div>
					</div>
				</div>
			</header>
			<main>


				