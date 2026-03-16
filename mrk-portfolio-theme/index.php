<?php
/**
 * Fallback index template.
 *
 * @package mrk-portfolio-theme
 */

get_header();
?>
<section class="section">
	<div class="section-heading">
		<h1><?php esc_html_e( 'Latest Content', 'mrk-portfolio-theme' ); ?></h1>
	</div>
	<div class="blog-list">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'project-card' ); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="muted"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="muted"><?php esc_html_e( 'No content found.', 'mrk-portfolio-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
