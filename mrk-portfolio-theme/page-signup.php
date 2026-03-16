<?php
/**
 * Template Name: Signup Page
 *
 * @package mrk-portfolio-theme
 */

if ( is_user_logged_in() ) {
	wp_safe_redirect( admin_url() );
	exit;
}

$signup_message = '';

if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['mrk_signup_nonce'] ) ) {
	if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mrk_signup_nonce'] ) ), 'mrk_signup_action' ) ) {
		$username = isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '';
		$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$password = isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] ) : '';

		$user_id = wp_create_user( $username, $password, $email );
		if ( is_wp_error( $user_id ) ) {
			$signup_message = '<p class="form-note">' . esc_html( $user_id->get_error_message() ) . '</p>';
		} else {
			$signup_message = '<p class="form-note">' . esc_html__( 'Registration successful. You can now login.', 'mrk-portfolio-theme' ) . '</p>';
		}
	}
}

get_header();
?>
<section class="auth-page">
	<div class="auth-card">
		<p class="eyebrow"><?php esc_html_e( 'Create account', 'mrk-portfolio-theme' ); ?></p>
		<h1><?php the_title(); ?></h1>
		<form method="post" class="auth-form">
			<label>
				<?php esc_html_e( 'Username', 'mrk-portfolio-theme' ); ?>
				<input type="text" name="username" required />
			</label>
			<label>
				<?php esc_html_e( 'Email', 'mrk-portfolio-theme' ); ?>
				<input type="email" name="email" required />
			</label>
			<label>
				<?php esc_html_e( 'Password', 'mrk-portfolio-theme' ); ?>
				<input type="password" name="password" required />
			</label>
			<?php wp_nonce_field( 'mrk_signup_action', 'mrk_signup_nonce' ); ?>
			<button class="button primary" type="submit"><?php esc_html_e( 'Create Account', 'mrk-portfolio-theme' ); ?></button>
		</form>
		<?php echo wp_kses_post( $signup_message ); ?>
		<p class="form-note">
			<a href="<?php echo esc_url( home_url( '/login/' ) ); ?>"><?php esc_html_e( 'Already have an account? Login', 'mrk-portfolio-theme' ); ?></a>
		</p>
	</div>
</section>
<?php
get_footer();
