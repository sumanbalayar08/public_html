	</main>
		<footer class="">
			<div class="sec-wrapper custom-px p80">
				<?php if( get_field('whatsapp_link', 'options') ): ?>
					<div class="whatsapp-icon shake">
						<a href="<?php echo get_field('whatsapp_link', 'options')['url']; ?>" target="_blank">
							<?php $whatsapp_icon = get_field('whatsapp_icon', 'options'); ?>
							<?php echo get_custom_image($whatsapp_icon['ID'], false, $class = '','WhatsApp Logo'); ?>
							<!-- <img src="<?php echo get_template_directory_uri(); ?>/assets/images/whatsapp-svg.png" width="68"> -->
						</a>
					</div>
				<?php endif; ?>
				<div class="row">
					<div class="col-12 col-md-6 d-md-flex">
						<div class="col-12 col-md-4 mb-5 mb-md-0">
							<div class="footer__logo">
								<a href="<?php echo site_url(); ?>">
									<div class="img-w">
										<?php $footer_logo = get_field('footer_logo', 'options'); ?>
										<?php echo get_custom_image($footer_logo['ID'], false, $class = '','footer Logo'); ?>
									</div>
								</a>
							</div>
						</div>
						<div class="subscribe col-12 col-md-8  mb-5 mb-md-0">
							<?php if( get_field('footer_subscribe_heading', 'options') ): ?>
								<h4 class="text-upper font24">
									<?php echo get_field('footer_subscribe_heading', 'options'); ?>
								</h4>
							<?php endif; ?>
							<div class="col-12 col-md-8">
								<?php if( get_field('footer_subscribe', 'options')['description']): ?>
									<?php echo get_field('footer_subscribe', 'options')['description']; ?>
								<?php endif; ?>
							</div>
							<div class="subscribe-form">
								<div>
									<?php //echo get_field('footer_subscribe', 'options')['form']; ?>
									<form class="col-12 col-md-10">
										<div class="d-flex">
											<input type="text" name="name" placeholder="Enter Your Email">
											<input type="submit" name="subscribe" class="btn" value="Subscribe">
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 d-md-flex" >
						<div class="col-12 col-md-4 mb-5 mb-md-0">
							<?php if( get_field('footer_social_links_heading', 'options') ): ?>
								<h4 class="text-upper font24"><?php echo get_field('footer_social_links_heading', 'options'); ?></h4>
							<?php endif; ?>
							<?php if( get_field('footer_social_links', 'options') ): ?>
								<ul class="footer__nav-content">
									<?php $footer_social_links = get_field('footer_social_links', 'options'); ?>
									<?php foreach( $footer_social_links as $key => $link ): ?>
									<li class="d-flex">
										<div class="col-1 me-3">
											<div class="hvr-buzz-out footer-social-icon">
												<div class="img-w">
													<?php echo get_custom_image($link['image']['ID'], false, $class = '','header Logo'); ?>
													<!-- <img src="images/facebook.png"> -->
												</div>
											</div>
										</div>
										<a href="<?php echo $link['link']['url']; ?>" class="link--hover" target="_blank"><?php echo $link['link']['title']; ?></a>
									</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
						<div class="col-12 col-md-8 mb-5 mb-md-0">
							<h4 class="text-upper font24">QUick Links</h4>
							<div class=" d-md-flex justify-content-between">
								<?php if(has_nav_menu( 'primary' )): ?>
									<?php
										wp_nav_menu(
											array(
												'theme_location' => 'footer-1',
												'menu_class' => 'footer__nav-content',
												'menu_item_class' => '',
												'menu_item_link_class' => '',
												'fallback_cb'    => false,
											)
										);
									?>
								<?php endif; ?>
								<?php if(has_nav_menu( 'primary' )): ?>
									<?php
										wp_nav_menu(
											array(
												'theme_location' => 'footer-2',
												'menu_class' => 'footer__nav-content',
												'menu_item_class' => '',
												'menu_item_link_class' => '',
												'fallback_cb'    => false,
											)
										);
									?>
								<?php endif; ?>
								<?php if(has_nav_menu( 'primary' )): ?>
									<?php
										wp_nav_menu(
											array(
												'theme_location' => 'footer-3',
												'menu_class' => 'footer__nav-content',
												'menu_item_class' => '',
												'menu_item_link_class' => '',
												'fallback_cb'    => false,
											)
										);
									?>
								<?php endif; ?>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="copyright pb-4 pb-md-0 custom-px ">
				<div class="row gx-0 py-4 ">
					<div class="col-12 col-md-6 mb-3 mb-md-0 mobile-center">
						<?php if( get_field('copyright_content', 'options') ): ?>
							<?php
								$copyRightText = get_field('copyright_content', 'options');
			                    $copyRightText = str_replace("[year]", date('Y'), $copyRightText);
			                    echo $copyRightText;
							?>
						<?php endif; ?>
					</div>
					<div class="col-12 col-md-6 text-right mobile-center">
						<?php if( get_field('website_by', 'options') ): ?>
							Website By <a href="<?php echo get_field('website_by', 'options')['url']; ?>" class="link--hover primary-color" target="_blank">Grow</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<!-- <img src="<?php echo get_template_directory_uri(); ?>/assets/images/circles-svg-same-color.svg" class="footer-dots" > -->
			<?php  get_template_part( 'template-parts/components/comp', 'arrow-svg', array('classname' => 'footer-dots','fadeimgClass' => 'fade-img--footer') ); ?>
		</footer>
	</div>

	<div class="slide-menu" style="">
		<div class="d-flex h-100vh align-items-start flex-column justify-content-between p-5">
			<div>
				<div class="logo">
					<a href="<?php echo site_url(); ?>">
						<div class="img-w">
							<?php $footer_logo = get_field('footer_logo', 'options'); ?>
							<?php echo get_custom_image($footer_logo['ID'], false, $class = '','footer Logo'); ?>
						</div>
					</a>
				</div>
			</div>
			<div class="nav flex-fill d-flex flex-column justify-content-center">
				<div class="slide-menu__nav-label h3-style mb-3 text-upper">Our services</div>
				<ul class="m-0 p-0 mb-5">
					<li class="p-1"><a href="about.php" class="link--hover">Sports Experiences</a></li>
					<li class="p-1"><a href="events.php" class="link--hover">Sponsorship</a></li>
				</ul>
				<div class="slide-menu__nav-label h3-style mb-3 text-upper">Company</div>
				<ul class="m-0 p-0">
					<li class="p-1"><a href="about.php" class="link--hover">About Us</a></li>
					<li class="p-1"><a href="contact.php" class="link--hover">Contact Us</a></li>
				</ul>
			</div>
			<!-- <div class="slide-menu__foooter">
				<div class="d-flex">
					<div class="pe-2"><div class="image-responsive hvr-buzz-out "><a href=""><img src="images/Instagram.svg"></a></div></div>
					<div class="pe-2"><div class="image-responsive hvr-buzz-out "><a href=""><img src="images/Twitter.svg"></a></div></div>
					<div><div class="image-responsive hvr-buzz-out "><a href=""><img src="images/Facebook.svg"></a></div></div>
				</div>
			</div> -->
		</div>
		<div class="close-slide-menu"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/close.svg" width="25"></div>
	</div> 

    <?php wp_footer(); ?>

</body>
</html>