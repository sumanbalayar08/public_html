<?php
	/**
	 * Template Name: Novelty Furniture Landing Page Template
	 * @package Expatsport
	 * @version 1.0
	 */
	wp_enqueue_style( 'novelty-furniture-landing-style', get_template_directory_uri().'/assets/css/novelty-furniture-landing.css', array('custom-style'), '1.0' );

	get_header(null, array('headerClass'=>'solid')); 

	if (have_posts()) :
?>
		<div class="novelty-furniture-landing elWithHeaderPadding">

			<div class="novelty-furniture-banner">
				<div class="novelty-furniture-banner__image" style="background-image: url(<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/banner.jpg)">
					<img src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/banner.jpg" width="902" />
				</div>
				<div class="novelty-furniture-banner__text">
					<h1 class="novelty-furniture-banner__title">Kick-start this event season with unique pieces of furniture</h1>
					<a href="#vintage-elegance" class="novelty-furniture-banner__cta c_button" data-hover="Learn More">Learn More</a>
				</div>
			</div>

			<div class="novelty-vintage" id="vintage-elegance">
				<h2 class="novelty-vintage__title">Furniture that adds vintage elegance to any setting</h2>

				<div class="novelty-vintage__pattern-one">
					<div class="novelty-vintage__pattern-one__each">
						<div class="novelty-vintage__pattern-one__each__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Copenhagen-Chair.jpg" width="320" />
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Copenhagen-Chair-black.jpg" width="320" />
						</div>
						<div class="novelty-vintage__pattern-one__each__text">
							<a class="novelty-vintage__pattern-one__each__title">Copenhagen Chair</a>
							<div class="novelty-vintage__pattern-one__each__dimensions">Dimensions: 50Lx61Wx45/85cmH</div>
							<div class="novelty-vintage__pattern-one__each__description">
								Smooth organic lines inspired by the curve of a protective shell.<br/><br/>Tip: Complement with Gatsby and Compass table; or pair it with the Bonnie armchair.
							</div>
						</div>
					</div>
					<div class="novelty-vintage__pattern-one__each">
						<div class="novelty-vintage__pattern-one__each__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Gatsby-Tables.jpg" width="320" />
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Gatsby-Tables-round.jpg" width="320" />
						</div>
						<div class="novelty-vintage__pattern-one__each__text">
							<a class="novelty-vintage__pattern-one__each__title">Gatsby Tables</a>
							<div class="novelty-vintage__pattern-one__each__dimensions">
								Round: 100Lx75cmH<br/>
								Square: 80Lx80Wx75cmH
							</div>
							<div class="novelty-vintage__pattern-one__each__description">
								Art Deco style inspired by 30’s; straight, smooth & streamlined collumn design.<br/><br/>Tip: Perfect with our intimate Copenhagen chairs or Bonnie armchair.
							</div>
						</div>
					</div>
				</div>

				<div class="novelty-vintage__pattern-one-image">
					<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Copenhagen-Chair-Gatsby-Tables.jpg" width="1110" />
				</div>

				<div class="novelty-vintage__pattern-two">
					<div class="novelty-vintage__pattern-two__image">
						<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Bonnie-armchair-setting.jpg" width="610" />
					</div>
					<div class="novelty-vintage__pattern-two__main">
						<div class="novelty-vintage__pattern-two__main__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Bonnie-armchair.jpg" width="320" />
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Bonnie-armchair-black.jpg" width="320" />
						</div>
						<div class="novelty-vintage__pattern-two__main__text">
							<a class="novelty-vintage__pattern-two__main__title">Bonnie Armchair</a>
							<div class="novelty-vintage__pattern-two__main__dimensions">
								53Lx58Wx47/78cmH
							</div>
							<div class="novelty-vintage__pattern-two__main__description">
								Subtle curves to the high back and arms, plush seating.<br/><br/>Tip: Pair with our indulgent Gatsby tables or our Copenhagen chair.
							</div>
						</div>
					</div>
				</div>

				<div class="novelty-vintage__pattern-three">
					<div class="novelty-vintage__pattern-three__image">
						<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Round-Ottoman-setting.jpg" width="610" />
					</div>
					<div class="novelty-vintage__pattern-three__main">
						<div class="novelty-vintage__pattern-three__main__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Round-Ottoman.jpg" width="320" />
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Round-Ottoman-1.jpg" width="320" />
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Round-Ottoman-2.jpg" width="320" />
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Round-Ottoman-3.jpg" width="320" />
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Round-Ottoman-4.jpg" width="320" />
						</div>
						<a class="novelty-vintage__pattern-three__main__title">Round Ottoman</a>
						<div class="novelty-vintage__pattern-three__main__dimensions">
							110Dx45cmH
						</div>
						<div class="novelty-vintage__pattern-three__main__description">
							Scrumptious smooth curves, luxurious velvet fabric, available in Beige and Old Rose to Mint Green, Dark Green and Royal Blue.<br/><br/>
							Tip: Complement with large indoor plants or our VIP seating selections.
							
						</div>
					</div>
				</div>
			</div>

			<div class="geometric-collection" id="geometric-collection">
				<h2 class="geometric-collection__title">Geometric Collection</h2>
				<div class="geometric-collection__desc">This collection will delight all design lovers. White metal is formed into an unpretentious and urbane geometric design that is strong but light.</div>

				<div class="geometric-collection__main">
					<div class="geometric-collection__main__image">
						<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Geometric-Collection.jpg" width="1080" />
					</div>
					<div class="geometric-collection__main__text">
						<div class="geometric-collection__main__each">
							<div>
								<a class="geometric-collection__main__each__title">Geometric Stool</a>
								<div class="geometric-collection__main__each__dimensions">
									49Lx52Wx76.5/114cmH
								</div>
								<div class="geometric-collection__main__each__description">
									Matte white metal, a comfortable seat with lightly & slightly splayed legs.
								</div>
							</div>
						</div>
						<div class="geometric-collection__main__each">
							<div>
								<a class="geometric-collection__main__each__title">Geometric Cocktail Table</a>
								<div class="geometric-collection__main__each__dimensions">
									70Dx110cmH
								</div>
								<div class="geometric-collection__main__each__description">
									Unpretentious and urbane geometric white metal design.
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="geometric-collection__main">
					<div class="geometric-collection__main__image">
						<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Linear-and-geo-chair-setting.jpg" width="1080" />
					</div>
					<div class="geometric-collection__main__text">
						<div class="geometric-collection__main__each">
							<div>
								<a class="geometric-collection__main__each__title">Linear table</a>
								<div class="geometric-collection__main__each__dimensions">
									Small 80Lx80Wx75cmH<br/>
									Medium 190Lx90Wx75cmH
								</div>
								<div class="geometric-collection__main__each__description">
									Sleek white top, metal legs, soft & modern look.
								</div>
							</div>
						</div>
						<div class="geometric-collection__main__each">
							<div>
								<a class="geometric-collection__main__each__title">Geometric chair</a>
								<div class="geometric-collection__main__each__dimensions">
									61.5Lx51Wx46/81cmH
								</div>
								<div class="geometric-collection__main__each__description">
									Angular and geometric, it is smart and understated. Curved with strong yet slender legs.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modern-table" id="modern-table">
				<h2 class="modern-table__title">Modern Table</h2>
				<div class="modern-table__desc">Understated but powerful, this piece can uplift any setting, whether formal or casual.</div>
				<div class="modern-table__images">
					<div class="modern-table__images__each">
						<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Modern-table-1.jpg" width="540" />
					</div>
					<div class="modern-table__images__each">
						<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Modern-table-2.jpg" width="540" />
					</div>
				</div>
				<div class="modern-table__text">
					<div class="modern-table__text__dimensions">
						Small 80Lx80Wx75.5cmH<br/>
						Medium 190Lx80Wx75.5cmH<br/>
						Large 240Lx80Wx75.5cmH
					</div>
				</div>
			</div>

			<div class="section-chairs" id="armchairs">
				<h2 class="section-chairs__title">Take a Seat</h2>
				<div class="section-chairs__main">
					<div class="section-chairs__main__each">
						<div class="section-chairs__main__each__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Wings-Armchair.jpg" width="425" />
						</div>
						<div class="section-chairs__main__each__text">
							<a class="section-chairs__main__each__title">Contemporary Bar Stool</a>
							<div class="section-chairs__main__each__dimensions">Dimensions: 32Lx44Wx70/80cmH</div>
							<div class="section-chairs__main__each__description">
								The Contemporary bar stool is a study in matte white metal minimalism, stylish and comfortable.
								<br/><br/>Tip: An ideal height for any bar or our Spider cocktail table
							</div>
						</div>
					</div>
					<div class="section-chairs__main__each">
						<div class="section-chairs__main__each__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Wings-Armchair-2.jpg" width="425" />
						</div>
						<div class="section-chairs__main__each__text">
							<a class="section-chairs__main__each__title">Wings Armchair</a>
							<div class="section-chairs__main__each__dimensions">Dimensions: 70Lx64Wx43/80cmH</div>
							<div class="section-chairs__main__each__description">
								Confident curves and contours, supremely comfortable seating.<br/><br/>Tip: Pair this timeless statement piece with our Tulip or Cross Round tables or let it stand alone.
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="new-variations">
				<h2 class="new-variations__title">Discover New Variations</h2>
				<div class="new-variations__main">
					<div class="new-variations__each">
						<div class="new-variations__each__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Wire-coffee-table-white.jpg" width="332" />
						</div>
						<div class="new-variations__each__title">Wire Coffee Table White</div>
					</div>
					<div class="new-variations__each">
						<div class="new-variations__each__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Industrial-coffee-table-white.jpg" width="332" />
						</div>
						<div class="new-variations__each__title">Industrial Coffee Table White</div>
					</div>
					<div class="new-variations__each">
						<div class="new-variations__each__image">
							<img loading="lazy" src="<?php bloginfo( 'template_url' ); ?>/assets/img/novelty-furniture/Royal-Sofa-White-Side.jpg" width="332" />
						</div>
						<div class="new-variations__each__title">Royal Sofa White</div>
					</div>
				</div>
			</div>
		</div>
<?php
	if(get_the_content() != ""){
		echo "<section class='content-page-wysiwyg container' id='content-page'>";
		the_content();
		echo "</section>";
	}

	// content
	get_template_part( 'template-parts/content/content', 'acf' );

	endif;
	wp_reset_query();

	get_footer();
?>