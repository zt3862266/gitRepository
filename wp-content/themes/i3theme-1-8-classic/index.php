<?php get_header(); ?>
	<div id="content-padding">
    <div id="content">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

        <div class="post" id="post-<?php the_ID(); ?>">
		  <table id="post-head">
		  <tr>
		  	<td id="head-date">
			  <div class="date"><span><?php the_time('M') ?></span> <?php the_time('d') ?></div>
		  	</td>
		  	<td>
		  <div class="title">
          <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
          <div class="postdata"><span class="category"><?php the_category(', ') ?></span> <span class="comments"><?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></span></div>
		  </div>
		  	</td>
		  </tr>
		  </table>
		  
          <div class="entry">
            <?php the_content('Continue reading &raquo;'); ?>
            
            <p class="submeta">written by <strong><?php the_author(); ?></strong> 
			<?php 
				if(function_exists("the_tags"))
					the_tags('\\\\ tags: ', ', ', '<br />'); 					
			?>
            </p>
          </div><!--/entry -->
          
        </div><!--/post -->

		<?php endwhile; ?>
		<div class="page-nav-left">
		<div class="page-nav-right">
        <div class="page-nav"> <span class="previous-entries"><?php next_posts_link('Previous Entries') ?></span> <span class="next-entries"><?php previous_posts_link('Next Entries') ?></span>
        </div></div>
        </div><!-- /page nav -->

	<?php else : ?>

		<h2>Not Found</h2>
		<p>Sorry, but you are looking for something that isn't here.</p>

	<?php endif; ?>

      </div>
	<div id="footer-left">
			<div id="footer-right">
      <div id="footer">Theme by MangoOrange.com & AlreadyHosting.com<br /> 
<?php 
include_once("real-footer.php");
?>
</div><!--/left-col -->

<?php 
$current_page = $post->ID; // Hack to prevent the no sidebar error
include_once("sidebar-right.php"); 
//get_sidebar();
?>

<?php get_footer(); ?>