<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle">Search Results</h2>
		
		
		<p class="navigation">
			<?php posts_nav_link(' - ','previous posts','next posts'); ?> 
		</p>


		<?php while (have_posts()) : the_post(); ?>
				
			<div class="post">
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>				
				<div class="search-content">
					<?php the_excerpt() ?>
				</div>
		
				<p class="post-meta">Posted on <?php the_time('F jS, Y') ?> in <?php the_category(', ') ?> by <?php the_author(); ?> | <?php edit_post_link('Edit','','|'); ?>  <?php comments_popup_link('leave a comment', '1 comment', '% comments'); ?></p>
			</div>
	
		<?php endwhile; ?>
		
		<p class="navigation">
			<?php posts_nav_link(' - ','previous posts','next posts'); ?> 
		</p>
	
	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>
		
	</div>

<?php get_sidebar(); get_footer(); ?>