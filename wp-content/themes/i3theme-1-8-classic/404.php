<?php get_header(); ?>
<div id="content-padding">
  <div id="content">
      
    <div class="post">
        <h2>Error 404 - Not Found</h2>
		
		<div class="entry">
		<p>Sorry, the page that you are looking for does not exist.</p>	
		</div><!--/entry -->
	
	</div><!--/post -->
	
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