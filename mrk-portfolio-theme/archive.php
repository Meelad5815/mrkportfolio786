<?php
/**
 * Archive template.
 *
 * @package mrk-portfolio-theme
 */

get_header();
?>
<section class="section">
	<div class="section-heading">
		<h1><?php the_archive_title(); ?></h1>
		<?php the_archive_description( '<div class="muted">', '</div>' ); ?>
	</div>
	<div class="project-grid">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'project-card' ); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="muted"><?php the_excerpt(); ?></div>
			</article>
		<?php endwhile; else : ?>
			<p><?php esc_html_e( 'No content found.', 'mrk-portfolio-theme' ); ?></p>
		<?php endif; ?>
	</div>
	<?php the_posts_pagination(); ?>
</section>
<?php
get_footer();
