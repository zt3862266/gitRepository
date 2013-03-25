<?php get_header(); ?>

	<div id="content">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
			<div class="page-content">
				<?php the_content(); ?>
	
				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
	
			</div>
		</div>
	  <?php endwhile; endif; ?>
	<?php edit_post_link('Edit', '<p>', '</p>'); ?>
	</div>

<?php get_sidebar(); get_footer(); ?>