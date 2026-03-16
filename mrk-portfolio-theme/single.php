<?php
/**
 * Single post template.
 *
 * @package mrk-portfolio-theme
 */

get_header();
?>
<section class="section">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'project-card page-content' ); ?>>
			<h1><?php the_title(); ?></h1>
			<p class="form-note"><?php echo esc_html( get_the_date() ); ?></p>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="single-thumbnail"><?php the_post_thumbnail( 'large' ); ?></div>
			<?php endif; ?>
			<?php the_content(); ?>
			<?php if ( 'project' === get_post_type() ) : ?>
				<div class="project-links">
					<?php $github = get_post_meta( get_the_ID(), '_mrk_github_link', true ); ?>
					<?php $live   = get_post_meta( get_the_ID(), '_mrk_live_demo', true ); ?>
					<?php if ( $github ) : ?><a class="button ghost" href="<?php echo esc_url( $github ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'GitHub', 'mrk-portfolio-theme' ); ?></a><?php endif; ?>
					<?php if ( $live ) : ?><a class="button primary" href="<?php echo esc_url( $live ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Live Demo', 'mrk-portfolio-theme' ); ?></a><?php endif; ?>
				</div>
			<?php endif; ?>
		</article>
	<?php endwhile; endif; ?>
</section>
<?php
get_footer();
