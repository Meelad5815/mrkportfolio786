<?php
/**
 * Theme header template.
 *
 * @package mrk-portfolio-theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<nav class="nav" aria-label="<?php esc_attr_e( 'Main navigation', 'mrk-portfolio-theme' ); ?>">
		<div class="brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		</div>
		<div class="nav-actions">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'primary-menu',
					'fallback_cb'    => 'wp_page_menu',
				)
			);
			?>
			<a class="nav-link" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'Projects', 'mrk-portfolio-theme' ); ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a class="nav-link" href="<?php echo esc_url( admin_url() ); ?>"><?php esc_html_e( 'Admin Panel', 'mrk-portfolio-theme' ); ?></a>
				<a class="nav-link" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Logout', 'mrk-portfolio-theme' ); ?></a>
			<?php else : ?>
				<a class="nav-link" href="<?php echo esc_url( home_url( '/login/' ) ); ?>"><?php esc_html_e( 'Login', 'mrk-portfolio-theme' ); ?></a>
				<a class="nav-link" href="<?php echo esc_url( home_url( '/signup/' ) ); ?>"><?php esc_html_e( 'Sign Up', 'mrk-portfolio-theme' ); ?></a>
			<?php endif; ?>
			<button class="theme-toggle" id="themeToggle" type="button" aria-label="<?php esc_attr_e( 'Toggle dark and light mode', 'mrk-portfolio-theme' ); ?>">
				<span class="toggle-icon" aria-hidden="true">☾</span>
				<span class="toggle-text"><?php esc_html_e( 'Dark', 'mrk-portfolio-theme' ); ?></span>
			</button>
		</div>
	</nav>
</header>
<main id="content" class="site-main">
