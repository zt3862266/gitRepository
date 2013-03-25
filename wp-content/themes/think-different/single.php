<?php get_header(); ?>

	<div id="content">
				
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="post" id="post-<?php the_ID(); ?>">
			<h2 class="post-title"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h2>
	
			<div class="post-content">
				<?php the_content(); ?>
	
				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
	
				<p class="post-meta">
						posted on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?> and filed under <?php the_category(', ') ?> -    
						
						<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Both Comments and Pings are open ?>
							 <?php comments_rss_link('comments feed'); ?> | <a href="#respond">leave a comment</a> | <a href="<?php trackback_url(true); ?>" rel="trackback" title="right click and copy link">trackback url</a>
						
						<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
							// Only Pings are Open ?>
							 <span class="closed">comments are closed</span> | <a href="<?php trackback_url(true); ?> " rel="trackback" title="right click and copy link">trackback url</a>.
						
						<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Comments are open, Pings are not ?>
							 <?php comments_rss_link('comments feed'); ?> | <a href="#respond">leave a comment</a> | <span class="closed">trackbacks are closed</span>
			
						<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
							// Neither Comments, nor Pings are open ?>
							  <span class="closed">comments and trackbacks are closed</span>			
						
						<?php } edit_post_link('edit',' | ',''); ?>
				</p>
	
			</div><!-- end post content -->
		</div><!-- end post -->
		
	<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
		<p>Sorry, no posts matched your criteria.</p>
	
<?php endif; ?>
</div><!-- end content -->
<?php get_sidebar(); get_footer(); ?>
