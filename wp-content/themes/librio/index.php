<?php get_header(); ?>
	
<div id="content">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
				<h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

				<?php the_content('Read the rest of this entry &raquo;'); ?>
				
				<div class="post-info">
					<?php the_time('F jS, Y') ?> in
					<?php the_category(', '); ?>
					<?php the_tags('| tags: ', ', ', ''); ?> | 
					<?php comments_popup_link('<strong>No Comments</strong>', '<strong>1 Comment</strong>', '<strong>% Comments</strong>'); ?>
				</div>
			</div>
			
			<?php comments_template(); ?>
		<?php endwhile; ?>

		<div id="pages">
			<a href="#"><?php next_posts_link('&larr;Older') ?></a>&nbsp;&nbsp;&nbsp;<a href="#"><?php previous_posts_link('Newer&rarr;') ?></a>
		</div>

	<?php else : ?>

		<h1>Not Found</h1>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>
	
</div>
		
<?php get_sidebar(); ?>
<?php get_footer(); ?>
		