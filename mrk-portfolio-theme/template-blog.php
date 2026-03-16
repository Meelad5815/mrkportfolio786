<?php
/**
 * Template Name: Blog Page
 *
 * @package mrk-portfolio-theme
 */

get_header();
?>
<section class="section">
	<div class="section-heading">
		<p class="eyebrow"><?php esc_html_e( 'Blog', 'mrk-portfolio-theme' ); ?></p>
		<h1><?php the_title(); ?></h1>
	</div>
	<div class="project-grid">
		<?php
		$blog_query = new WP_Query(
			array(
				'post_type'      => 'post',
				'paged'          => get_query_var( 'paged', 1 ),
				'posts_per_page' => 6,
			)
		);
		if ( $blog_query->have_posts() ) :
			while ( $blog_query->have_posts() ) :
				$blog_query->the_post();
				?>
				<article <?php post_class( 'project-card' ); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="form-note"><?php echo esc_html( get_the_date() ); ?></p>
					<div class="muted"><?php the_excerpt(); ?></div>
				</article>
				<?php
			endwhile;
			wp_reset_postdata();
		else :
			?>
			<p><?php esc_html_e( 'No blog posts found.', 'mrk-portfolio-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
