<?php
/**
 * Blog home template.
 *
 * @package mrk-portfolio-theme
 */

get_header();
?>
<section class="section">
	<div class="section-heading">
		<p class="eyebrow"><?php esc_html_e( 'Blog', 'mrk-portfolio-theme' ); ?></p>
		<h1><?php esc_html_e( 'Latest Blog Posts', 'mrk-portfolio-theme' ); ?></h1>
	</div>
	<div class="blog-list">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'project-card' ); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="form-note"><?php echo esc_html( get_the_date() ); ?></p>
					<div class="muted"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="muted"><?php esc_html_e( 'No blog posts found.', 'mrk-portfolio-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
