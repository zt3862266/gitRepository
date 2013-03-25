<?php get_header(); ?>
	<!-- main -->
	<div id="main" class="corner10TL">
    	<?php if (have_posts()) : while (have_posts()) : the_post(); update_post_caches($posts); ?>
      	<div class="post">
			<div class="right">
				<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
            	<p class="post-info">Filed under  <?php the_tags('', ', ', ''); ?></p>
               	<?php the_content('Continue reading'); ?>
				
			</div>
            
            <?php if (getPostThumbnail($post->ID, false)!='') :?>
			<div class="left corner10">   
                <a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" rel="nofollow">
                	<img alt="<?php the_title(); ?>" title="<?php the_title(); ?>" src="<?php echo(getPostThumbnail($post->ID, false));?>"/>
                </a>
            </div>
            <?php endif; ?>


            <div class="left corner10">
            	

            	<p class="dateinfo"><?php the_time(__('M')) ?><span><?php the_time(__('j')) ?></span></p>

               	<div class="post-meta">
                  	<h4>Post Info</h4>
                	<ul>
                    	<li class="user"><?php the_author_posts_link(); ?></li>
                        <li class="time"><?php get_time_link();?></li>
                        <li class="comment"><?php comments_popup_link(__('No comments'), __('1 comment'), __('% comments'), '', __('Comments off')); ?></li>
                        <li class="permalink"><?php echo('<a class="permalink" href="'.get_permalink($post->ID).'#more-'.$post->ID.'">Permalink</a>');?></li>
                    </ul>
				</div>
             </div>
			</div>
            
				<?php endwhile; ?> 
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