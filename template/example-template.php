<?php
// Template variables
$post_id         = get_the_ID();
$thumbnail       = ( $t = get_the_post_thumbnail( $post_id ) ) ? $t : false;
$load_more       =  $paged && $paged > 1 && $post_count && $posts_per_page && $post_count % $posts_per_page === 0 ? ' load-more' : '';
$permalink       = get_the_permalink( $post_id );
?>
<li class="panel<?php echo $load_more; ?>">
	<!-- Display the thumbnail -->
	<?php echo $thumbnail ? $thumbnail : ''; ?> 

	<!-- Display the title -->            
	<h2><a href="<?php echo $permalink; ?>"><?php the_title();?></a></h2>

	<!-- Display post meta -->  
	<p class="postMeta">
		<span class="date"><?php echo get_the_date(); ?></span>
		<span style="float:left; margin:0 5px; font-size:14px;">|</span>
		<span class="author"><?php echo get_the_author(); ?></span>
	</p>

	<!-- Display the article excerpt --> 
	<article><?php the_excerpt();?></article>

	<!-- Display more button --> 
	<a href="<?php echo $permalink; ?>" class="buttonMore">Continue Reading</a>
</li>