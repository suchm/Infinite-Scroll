<?php 
/*
Template Name: Example page
*/

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
	?> 
	<div class="container">
			
		<section id="content">
		
			<div class="row">
				<div class="col-xs-12 col-md-9" style="float:right;">

					<ul class="infinite-scroll">

						<?php
						// Display all posts up to the selected page number on load
						$post_type       = "{post_type}";
						$paged           = get_paged();
						$posts_per_page  = 10;
						$display_on_load = $paged * $posts_per_page;
						$template        = 'example';

						$args = array(
							'post_type' => $publication,
							'posts_per_page' => $display_on_load,
							'post_status' => $post_type,
						);

						$loop = new WP_Query( $args );
						$post_count = 1;
						while ( $loop->have_posts() ) : $loop->the_post();
							include( locate_template('templates/' . $template . '.php' ) );
							$post_count++;
						endwhile;
						wp_reset_query();

						// Reset the the number of posts per page after initial load
						$args['posts_per_page'] = $posts_per_page;
						wp_localize_script( 'infinite_scroll', 'is',
							array( 
								'ajaxurl'       => admin_url( 'admin-ajax.php' ),
								'wp_query_args' => $args,
								'page'          => $paged,
								'template'      => $template,
								'offset'        => 2500
							)
						);
						wp_enqueue_script('infinite_scroll');
						?>

					</ul>

				</div>

				<div class="col-xs-12 col-md-3">
					<!-- Add sidebar html here -->
				</div>
				
			</div>
			
		</section><!--end .infinite-scroll-->
			
	</div>

<?php endwhile; ?>
<?php else: ?>
	<p>Nothing here...</p>
<?php endif; ?>

<?php get_footer(); ?>