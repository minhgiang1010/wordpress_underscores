<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script src="https://kit.fontawesome.com/96822f0891.js" crossorigin="anonymous"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >

<div class="wrapper">
	<header class="site-header">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$underscores_description = get_bloginfo( 'description', 'display' );
			if ( $underscores_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $underscores_description; /* WPCS: xss ok. */ ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav class="main-navigation">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-1',
				'menu_class'	 => 'nav-menu',
				'menu_id'        => 'primary-menu',
				'container'		 => false, // bỏ cái wrapper div (không cần)
				'walker' => new HuuTien_Menu_Walker_Alecaddd_Mega()
			) );
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div class="carousel">
		<button class="carousel__button carousel__button--left">
			<i class="fas fa-angle-left"></i>
		</button>

		<div class="carousel__track-container">
			<ul class="carousel__track">
				<li class="carousel__slide current-slide">
					<img class="carousel__image" src="https://source.unsplash.com/random/650x650" alt="">
				</li>
				<li class="carousel__slide">
					<img class="carousel__image" src="https://source.unsplash.com/random/650x650" alt="">
				</li>
				<li class="carousel__slide">
					<img class="carousel__image" src="https://source.unsplash.com/random/650x650" alt="">
				</li>
			</ul>
		</div>
		<button class="carousel__button carousel__button--right">
			<i class="fas fa-angle-right"></i>
		</button>

		<div class="carousel__nav">
			<button class="carousel__indicator current-slide"></button>
			<button class="carousel__indicator"></button>
			<button class="carousel__indicator"></button>
		</div>
	</div>

	<div id="content" class="site-content">