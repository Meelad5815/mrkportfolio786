<?php
/**
 * MRK Portfolio Theme functions.
 *
 * @package mrk-portfolio-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup.
 */
function mrk_portfolio_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 60, 'width' => 220, 'flex-width' => true ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'mrk-portfolio-theme' ),
			'footer'  => __( 'Footer Menu', 'mrk-portfolio-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'mrk_portfolio_theme_setup' );

/**
 * Enqueue front-end assets.
 */
function mrk_portfolio_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'mrk-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600&display=swap', array(), null );
	wp_enqueue_style( 'mrk-main-style', get_template_directory_uri() . '/assets/css/style.css', array(), $version );

	wp_enqueue_script( 'mrk-theme-script', get_template_directory_uri() . '/assets/js/script.js', array(), $version, true );
}
add_action( 'wp_enqueue_scripts', 'mrk_portfolio_enqueue_assets' );

/**
 * Register widget areas.
 */
function mrk_portfolio_register_sidebars() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Widget Area 1', 'mrk-portfolio-theme' ),
			'id'            => 'footer-1',
			'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Widget Area 2', 'mrk-portfolio-theme' ),
			'id'            => 'footer-2',
			'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'mrk-portfolio-theme' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<section id="%1$s" class="sidebar-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'mrk_portfolio_register_sidebars' );

/**
 * Register custom post type for projects.
 */
function mrk_portfolio_register_projects_cpt() {
	$labels = array(
		'name'               => __( 'Projects', 'mrk-portfolio-theme' ),
		'singular_name'      => __( 'Project', 'mrk-portfolio-theme' ),
		'add_new'            => __( 'Add New', 'mrk-portfolio-theme' ),
		'add_new_item'       => __( 'Add New Project', 'mrk-portfolio-theme' ),
		'edit_item'          => __( 'Edit Project', 'mrk-portfolio-theme' ),
		'new_item'           => __( 'New Project', 'mrk-portfolio-theme' ),
		'view_item'          => __( 'View Project', 'mrk-portfolio-theme' ),
		'search_items'       => __( 'Search Projects', 'mrk-portfolio-theme' ),
		'not_found'          => __( 'No projects found', 'mrk-portfolio-theme' ),
		'not_found_in_trash' => __( 'No projects found in trash', 'mrk-portfolio-theme' ),
	);

	register_post_type(
		'project',
		array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-portfolio',
			'rewrite'      => array( 'slug' => 'projects' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		)
	);
}
add_action( 'init', 'mrk_portfolio_register_projects_cpt' );

/**
 * Add project details metabox.
 */
function mrk_portfolio_project_meta_box() {
	add_meta_box(
		'mrk_project_details',
		__( 'Project Details', 'mrk-portfolio-theme' ),
		'mrk_portfolio_project_meta_box_callback',
		'project',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'mrk_portfolio_project_meta_box' );

/**
 * Render project details metabox fields.
 *
 * @param WP_Post $post Post object.
 */
function mrk_portfolio_project_meta_box_callback( $post ) {
	wp_nonce_field( 'mrk_save_project_meta', 'mrk_project_meta_nonce' );

	$github_link  = get_post_meta( $post->ID, '_mrk_github_link', true );
	$live_demo    = get_post_meta( $post->ID, '_mrk_live_demo', true );
	$technologies = get_post_meta( $post->ID, '_mrk_technologies', true );
	?>
	<p>
		<label for="mrk_github_link"><strong><?php esc_html_e( 'GitHub Link', 'mrk-portfolio-theme' ); ?></strong></label><br />
		<input type="url" id="mrk_github_link" name="mrk_github_link" class="widefat" value="<?php echo esc_attr( $github_link ); ?>" />
	</p>
	<p>
		<label for="mrk_live_demo"><strong><?php esc_html_e( 'Live Demo Link', 'mrk-portfolio-theme' ); ?></strong></label><br />
		<input type="url" id="mrk_live_demo" name="mrk_live_demo" class="widefat" value="<?php echo esc_attr( $live_demo ); ?>" />
	</p>
	<p>
		<label for="mrk_technologies"><strong><?php esc_html_e( 'Technologies Used', 'mrk-portfolio-theme' ); ?></strong></label><br />
		<textarea id="mrk_technologies" name="mrk_technologies" class="widefat" rows="4" placeholder="e.g. WordPress, PHP, JavaScript"><?php echo esc_textarea( $technologies ); ?></textarea>
	</p>
	<?php
}

/**
 * Save project details metabox values.
 *
 * @param int $post_id Post ID.
 */
function mrk_portfolio_save_project_meta( $post_id ) {
	if ( ! isset( $_POST['mrk_project_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mrk_project_meta_nonce'] ) ), 'mrk_save_project_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'mrk_github_link'  => '_mrk_github_link',
		'mrk_live_demo'    => '_mrk_live_demo',
		'mrk_technologies' => '_mrk_technologies',
	);

	foreach ( $fields as $form_field => $meta_key ) {
		if ( isset( $_POST[ $form_field ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $form_field ] ) );
			if ( 'mrk_github_link' === $form_field || 'mrk_live_demo' === $form_field ) {
				$value = esc_url_raw( $value );
			}
			update_post_meta( $post_id, $meta_key, $value );
		}
	}
}
add_action( 'save_post_project', 'mrk_portfolio_save_project_meta' );

/**
 * Register Elementor locations.
 *
 * @param object $elementor_theme_manager Elementor manager.
 */
function mrk_portfolio_register_elementor_locations( $elementor_theme_manager ) {
	if ( is_object( $elementor_theme_manager ) && method_exists( $elementor_theme_manager, 'register_all_core_locations' ) ) {
		$elementor_theme_manager->register_all_core_locations();
	}
}
add_action( 'elementor/theme/register_locations', 'mrk_portfolio_register_elementor_locations' );
