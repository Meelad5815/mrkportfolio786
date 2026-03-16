<?php
/**
 * Front Page template.
 *
 * @package mrk-portfolio-theme
 */

get_header();
?>
<section class="hero" id="home">
	<div class="hero-content">
		<p class="eyebrow"><?php esc_html_e( 'Full Stack Web Developer', 'mrk-portfolio-theme' ); ?></p>
		<h1><?php bloginfo( 'name' ); ?></h1>
		<p class="hero-tagline"><?php bloginfo( 'description' ); ?></p>
		<div class="hero-actions">
			<a class="button primary" href="#contact"><?php esc_html_e( 'Hire Me', 'mrk-portfolio-theme' ); ?></a>
			<a class="button secondary" href="#contact"><?php esc_html_e( 'Contact Me', 'mrk-portfolio-theme' ); ?></a>
		</div>
		<div class="hero-stats">
			<div><h3><?php esc_html_e( 'Trustworthy', 'mrk-portfolio-theme' ); ?></h3><p><?php esc_html_e( 'Client-first delivery and transparent communication.', 'mrk-portfolio-theme' ); ?></p></div>
			<div><h3><?php esc_html_e( 'Professional', 'mrk-portfolio-theme' ); ?></h3><p><?php esc_html_e( 'Modern design systems with performance in mind.', 'mrk-portfolio-theme' ); ?></p></div>
			<div><h3><?php esc_html_e( 'Growth', 'mrk-portfolio-theme' ); ?></h3><p><?php esc_html_e( 'Always learning to keep your product ahead.', 'mrk-portfolio-theme' ); ?></p></div>
		</div>
	</div>
	<div class="hero-card">
		<h2><?php esc_html_e( 'Professional Profile', 'mrk-portfolio-theme' ); ?></h2>
		<p><?php esc_html_e( 'Passionate about crafting seamless user experiences, building efficient back-end systems, and delivering complete digital solutions.', 'mrk-portfolio-theme' ); ?></p>
		<ul>
			<li><?php esc_html_e( 'Clean, maintainable code', 'mrk-portfolio-theme' ); ?></li>
			<li><?php esc_html_e( 'Mobile-first responsive design', 'mrk-portfolio-theme' ); ?></li>
			<li><?php esc_html_e( 'Modern UI/UX patterns', 'mrk-portfolio-theme' ); ?></li>
		</ul>
	</div>
</section>

<section class="section" id="projects">
	<div class="section-heading">
		<p class="eyebrow"><?php esc_html_e( 'Projects', 'mrk-portfolio-theme' ); ?></p>
		<h2><?php esc_html_e( 'Portfolio projects managed from WordPress admin.', 'mrk-portfolio-theme' ); ?></h2>
	</div>
	<div class="project-grid">
		<?php
		$project_query = new WP_Query(
			array(
				'post_type'      => 'project',
				'posts_per_page' => 6,
			)
		);
		if ( $project_query->have_posts() ) :
			while ( $project_query->have_posts() ) :
				$project_query->the_post();
				$github_link = get_post_meta( get_the_ID(), '_mrk_github_link', true );
				$live_demo   = get_post_meta( get_the_ID(), '_mrk_live_demo', true );
				$tech        = get_post_meta( get_the_ID(), '_mrk_technologies', true );
				?>
				<article <?php post_class( 'project-card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="project-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
					<?php endif; ?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="muted"><?php the_excerpt(); ?></div>
					<?php if ( $tech ) : ?><p class="form-note"><strong><?php esc_html_e( 'Technologies:', 'mrk-portfolio-theme' ); ?></strong> <?php echo esc_html( $tech ); ?></p><?php endif; ?>
					<div class="project-links">
						<?php if ( $github_link ) : ?><a class="button ghost" href="<?php echo esc_url( $github_link ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'GitHub', 'mrk-portfolio-theme' ); ?></a><?php endif; ?>
						<?php if ( $live_demo ) : ?><a class="button primary" href="<?php echo esc_url( $live_demo ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Live Demo', 'mrk-portfolio-theme' ); ?></a><?php endif; ?>
					</div>
				</article>
				<?php
			endwhile;
			wp_reset_postdata();
		else :
			?>
			<p class="muted"><?php esc_html_e( 'No projects yet. Add projects from Dashboard → Projects.', 'mrk-portfolio-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="section" id="blog">
	<div class="section-heading">
		<p class="eyebrow"><?php esc_html_e( 'Blog', 'mrk-portfolio-theme' ); ?></p>
		<h2><?php esc_html_e( 'Latest posts from your WordPress blog.', 'mrk-portfolio-theme' ); ?></h2>
	</div>
	<div class="project-grid">
		<?php
		$blog_query = new WP_Query(
			array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
			)
		);
		if ( $blog_query->have_posts() ) :
			while ( $blog_query->have_posts() ) :
				$blog_query->the_post();
				?>
				<article <?php post_class( 'project-card' ); ?>>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p class="form-note"><?php echo esc_html( get_the_date() ); ?></p>
					<div class="muted"><?php the_excerpt(); ?></div>
				</article>
				<?php
			endwhile;
			wp_reset_postdata();
		else :
			?>
			<p class="muted"><?php esc_html_e( 'No blog posts found.', 'mrk-portfolio-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="section contact" id="contact">
	<div class="section-heading">
		<p class="eyebrow"><?php esc_html_e( 'Contact', 'mrk-portfolio-theme' ); ?></p>
		<h2><?php esc_html_e( 'Let’s build something great together.', 'mrk-portfolio-theme' ); ?></h2>
	</div>
	<div class="contact-grid">
		<div class="contact-card page-content">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					the_content();
				}
			}
			?>
		</div>
		<div class="contact-details">
			<h3><?php esc_html_e( 'Get in touch', 'mrk-portfolio-theme' ); ?></h3>
			<p><?php esc_html_e( 'Interested in working together? Reach out and I’ll respond quickly with next steps.', 'mrk-portfolio-theme' ); ?></p>
			<div class="contact-card">
				<p><strong><?php esc_html_e( 'Email', 'mrk-portfolio-theme' ); ?></strong> hafizmuhammadmeeladraza@gmail.com</p>
				<p><strong><?php esc_html_e( 'WhatsApp', 'mrk-portfolio-theme' ); ?></strong> 03270447262</p>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
