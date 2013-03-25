<?php get_header(); ?>
<div id="content-padding">
  <div id="content">
  
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  		
      <div class="post-nav"> 
         <span class="previous"><?php previous_post_link('%link') ?></span> 
         <span class="next"><?php next_post_link('%link') ?></span>
      </div>  
   
      <div class="post" id="post-<?php the_ID(); ?>" style="margin-top: 0;">
         <table id="post-head">
		 <tr>
		  	<td id="head-date">
         	<div class="date"><span><?php the_time('M') ?></span> 
         	<?php the_time('d') ?></div>
         	</td>
         	
         	<td>
         	 <div class="title">
	            <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>">
					<?php the_title(); ?></a></h2>
	            <div class="postdata">
	               <span class="category"><?php the_category(', ') ?></span>
	               <span class="right mini-add-comment">
	               	  <a href="#respond">Add comments</a>
	               </span>
	            </div>
	         </div>
         	</td>
         </tr>
         </table>
         
         <div class="entry">
         <?php the_content('Continue reading &raquo;'); ?>
         <?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
         
         <p class="submeta">written by <strong><?php the_author(); ?></strong> 
         <?php 
         if(function_exists("the_tags"))
         the_tags('\\\\ tags: ', ', ', '<br />'); 					
         ?>
         </p>
         <?php edit_post_link('Edit', '', ''); ?>
         </div><!--/entry -->
         
         <?php comments_template(); ?>
      </div><!--/post -->
      
	<?php endwhile; else: ?>
                 
         <p style="margin-top:25px;">Sorry, no posts matched your criteria.</p>
         
	<?php endif; ?>

	</div><!--/content -->
<?php
include_once("real-footer.php");
?>
</div><!--/left-col -->

<?php 
$current_page = $post->ID; // Hack to prevent the no sidebar error
include_once("sidebar-right.php"); 
?>

  
<?php get_footer(); ?>

