<?php
/**
 * Template Name: Login Page
 *
 * @package mrk-portfolio-theme
 */

get_header();
?>
<section class="auth-page">
	<div class="auth-card">
		<p class="eyebrow"><?php esc_html_e( 'Welcome back', 'mrk-portfolio-theme' ); ?></p>
		<h1><?php the_title(); ?></h1>
		<p class="muted"><?php esc_html_e( 'Sign in to manage your portfolio and content.', 'mrk-portfolio-theme' ); ?></p>
		<?php
		wp_login_form(
			array(
				'redirect'       => admin_url(),
				'label_username' => __( 'Email or Username', 'mrk-portfolio-theme' ),
				'label_password' => __( 'Password', 'mrk-portfolio-theme' ),
				'label_log_in'   => __( 'Sign In', 'mrk-portfolio-theme' ),
			)
		);
		?>
		<p class="form-note">
			<?php esc_html_e( 'Need an account?', 'mrk-portfolio-theme' ); ?>
			<a href="<?php echo esc_url( home_url( '/signup/' ) ); ?>"><?php esc_html_e( 'Sign up here', 'mrk-portfolio-theme' ); ?></a>.
		</p>
	</div>
</section>
<?php
get_footer();
