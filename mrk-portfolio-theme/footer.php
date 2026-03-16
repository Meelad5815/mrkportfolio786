<?php
/**
 * Theme footer template.
 *
 * @package mrk-portfolio-theme
 */
?>
</main>
<footer class="footer">
	<div class="footer-widgets">
		<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
			<?php dynamic_sidebar( 'footer-1' ); ?>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
			<?php dynamic_sidebar( 'footer-2' ); ?>
		<?php endif; ?>
	</div>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'footer',
			'container'      => false,
			'menu_class'     => 'footer-links',
			'fallback_cb'    => false,
		)
	);
	?>
	<p class="copyright">
		&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.
		<?php esc_html_e( 'All rights reserved.', 'mrk-portfolio-theme' ); ?>
	</p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
