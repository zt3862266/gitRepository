<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>
		
		<?php while (have_posts()) : the_post(); ?>
				
			<div class="post" id="post-<?php the_ID(); ?>">
				<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				
				<div class="post-content">
					<?php the_content('<p class="read-more">Continue reading this entry &raquo;</p>'); ?>
				</div>
		
				<p class="post-meta">Posted on <?php the_time('F jS, Y') ?> in <?php the_category(', ') ?> by <?php the_author(); ?> | <?php edit_post_link('Edit','','|'); ?>  <?php comments_popup_link('leave a comment', '1 comment', '% comments'); ?></p>
			</div>
	
		<?php endwhile; ?>


		<p class="navigation">
			<?php posts_nav_link(' - ','recent posts','older posts'); ?> 
		</p>
		
	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>

	<?php endif; ?>

	</div>

<?php get_sidebar(); get_footer(); ?>
