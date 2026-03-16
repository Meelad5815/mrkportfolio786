<?php
/**
 * 404 template.
 *
 * @package mrk-portfolio-theme
 */

get_header();
?>
<section class="section auth-page">
	<div class="auth-card">
		<p class="eyebrow">404</p>
		<h1><?php esc_html_e( 'Page not found', 'mrk-portfolio-theme' ); ?></h1>
		<p class="muted"><?php esc_html_e( 'The page you are looking for does not exist or has moved.', 'mrk-portfolio-theme' ); ?></p>
		<a class="button primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Homepage', 'mrk-portfolio-theme' ); ?></a>
	</div>
</section>
<?php
get_footer();
