<?php get_header(); ?>
	<!-- main -->
	<div id="main" class="corner10TL">
    	<?php if (is_search()) : ?>
		<h3><?php printf( __('Search Results for &#8216;%1$s&#8217;'), wp_specialchars($s, 1) ); ?></h3>
		<?php else : ?>
        <h3>
			<?php
            // If this is a category archive
            if (is_category()) {
                printf( __('Archive for the &#8216;%1$s&#8217; Category'), single_cat_title('', false) );
            // If this is a tag archive
            } elseif (is_tag()) {
                printf( __('Posts Tagged &#8216;%1$s&#8217;'), single_tag_title('', false) );
            // If this is a daily archive
            } elseif (is_day()) {
                printf( __('Archive for %1$s'), get_the_time(__('F jS, Y', '')) );
            // If this is a monthly archive
            } elseif (is_month()) {
                printf( __('Archive for %1$s'), get_the_time(__('F, Y', '')) );
            // If this is a yearly archive
            } elseif (is_year()) {
                printf( __('Archive for %1$s'), get_the_time(__('Y', '')) );
            // If this is an author archive
            } elseif (is_author()) {
                _e('Author Archive');
            // If this is a paged archive
            } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
                _e('Blog Archives');
            }
            ?>
    	</h3>
        <?php endif; ?>
        <div class="navigation clear">
			<?php if (function_exists('previous_posts_link')) : ?>
            	<span><?php previous_posts_link(__('Newer Entries')); ?></span>
			<?php endif; ?>	     
            <?php if (function_exists('next_posts_link')) : ?>      
            	<span><?php next_posts_link(__('Older Entries')); ?></span>
            <?php endif; ?>	
		</div>
        <div class="fix"></div>
        	<ul class="archive">
    	<?php if (have_posts()) : while (have_posts()) : the_post(); update_post_caches($posts); ?>
			<li>
				<div class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></div>
				<div class="post-details">Posted on <?php get_time_link();?> | Filed under <?php the_tags('', ', ', ''); ?></div>
			</li>
		<?php endwhile; ?>
		</ul>
		<div class="fix"></div>
		<div class="navigation clear">
			<?php if (function_exists('previous_posts_link')) : ?>
            	<span><?php previous_posts_link(__('Newer Entries')); ?></span>
			<?php endif; ?>	     
            <?php if (function_exists('next_posts_link')) : ?>      
            	<span><?php next_posts_link(__('Older Entries')); ?></span>
            <?php endif; ?>	
		</div>
		<?php else : ?>				
		<div>
        <?php echo('Sorry, no posts matched your criteria.'); ?>
        	</div>
		<?php endif; ?>
        <!-- /main -->
		</div>

		<?php get_sidebar(); ?>
<?php get_footer(); ?>