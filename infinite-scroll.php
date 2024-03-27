<?php
/*
Plugin Name: MS Infinite scroll
Description: Loads additional content on scroll
Author: Michael Such
Version: 1.0.0
*/

class infinite_scroll {

    function __construct() {

    	if ( !defined( 'MS_INFINITE_SCROLL_PLUGIN_VERSION' ) ) {
		    define('MS_INFINITE_SCROLL_PLUGIN_VERSION', '1.0.0');
		}

    	// Enqueue scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Add video post type functionality
		add_action( 'wp_ajax_nopriv_load_more', array( $this, 'ajax_load_more' ) );
		add_action( 'wp_ajax_load_more', array( $this, 'ajax_load_more' ) );
    }

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		wp_register_script( 'infinite_scroll', untrailingslashit( plugin_dir_url( __FILE__ ) ) . '/js/is.js', array(), MS_INFINITE_SCROLL_PLUGIN_VERSION ); //is.js
	}

	/**
	 * Localize video popup script
	 */
	public function ajax_load_more() {
	
		$args = isset( $_POST['query'] ) && is_array( $_POST['query'] ) ? $_POST['query'] : false;
	    $args['paged'] = isset( $_POST['page'] ) ? esc_attr( $_POST['page'] ) : false;
	    $template = isset( $_POST['template'] ) ? sanitize_text_field( $_POST['template'] ) : false;

	    if ( $template && $args ) {
	        ob_start();
	        $loop = new WP_Query( $args );

	        $post_count = 1;
	        if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post();
	            include( locate_template('templates/' . $template . '.php' ) );
	            $post_count++;
	        endwhile; endif; wp_reset_postdata();

	        $data = ob_get_clean();
	        wp_send_json_success( $data );
	    }
	    wp_die();
	}
}

$orphan = new infinite_scroll();
?>