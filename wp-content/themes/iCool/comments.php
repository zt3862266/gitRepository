<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/comment.js"></script>

<?php if (!empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
	<div class="errorbox corner10">
		<?php _e('Enter your password to view comments.', 'twellow'); ?>
	</div>
<?php return; endif; ?>

<?php
	$options = get_option('twellow_options');
	// for WordPress 2.7 or higher
	if (function_exists('wp_list_comments')) {
		$trackbacks = $comments_by_type['pings'];
	// for WordPress 2.6.3 or lower
	} else {
		$trackbacks = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' AND (comment_type = 'pingback' OR comment_type = 'trackback') ORDER BY comment_date", $post->ID));
	}
?>

<?php if(comments_open()) : ?>
<div class="navigation clear">
      
	<span class="addcomment"><a href="#respond"><?php _e('Leave a comment', 'twellow'); ?></a></span>
<?php endif; ?>
<?php if(pings_open()) : ?>
	<span class="addtrackback"><a href="<?php trackback_url(); ?>"><?php _e('Trackback', 'twellow'); ?></a></span>
</div>
<div class="fix"></div>
<?php endif; ?>


<?php if ($comments || comments_open()) : ?>
	<!-- trackbacks START -->
	<?php if (pings_open()) : ?>
    <h4><?php _e('Trackbacks', 'twellow'); echo (' (' . count($trackbacks) . ')'); ?></h4>
	<div id="commentlist">
    	<ol id="thetrackbacks">
			<?php if ($trackbacks) : $trackbackcount = 0; ?>
				<?php foreach ($trackbacks as $comment) : ?>
					<li class="trackback corner10">
						<div class="date">
							<?php printf( __('%1$s at %2$s', 'twellow'), get_comment_time(__('F jS, Y', 'twellow')), get_comment_time(__('H:i', 'twellow')) ); ?>
							 | <a href="#comment-<?php comment_ID() ?>"><?php printf('#%1$s', ++$trackbackcount); ?></a>
						</div>
						<div class="act">
							<?php edit_comment_link(__('Edit', 'twellow'), '', ''); ?>
						</div>
						<div class="fixed"></div>
						<div class="title">
							<a href="<?php comment_author_url() ?>">
								<?php comment_author(); ?>
							</a>
						</div>
					</li>
				<?php endforeach; ?>

			<?php else : ?>
				<li class="messagebox corner10">
					<span><?php _e('No trackbacks yet.', 'twellow'); ?></span>
				</li>

			<?php endif; ?>
		</ol>
		<div class="fix"></div>
		<!-- trackbacks END -->
	</div>
	<?php endif; ?>
	
    
    
    <h4><?php _e('Comments', 'twellow'); echo (' (' . (count($comments)-count($trackbacks)) . ')'); ?></h4>
    <div id="commentlist">
    <!-- comments START -->
        <ol id="thecomments">
        <?php
            if ($comments && count($comments) - count($trackbacks) > 0) {
                // for WordPress 2.7 or higher
                if (function_exists('wp_list_comments')) {
                    wp_list_comments('type=comment&callback=custom_comments');
                // for WordPress 2.6.3 or lower
                } else {
                    foreach ($comments as $comment) {
                        if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') {
                            custom_comments($comment, null, null);
                        }
                    }
                }
            } else {
        ?>
            <li class="messagebox corner10">
                <span><?php _e('No comments yet.', 'twellow'); ?></span>
            </li>
        <?php
            }
        ?>
        </ol>
        <!-- comments END -->
        <div class="fix"></div>
	</div>
	
    

<?php

		$comment_pages = paginate_comments_links('echo=0');
		if ($comment_pages) {
?>
		<div id="commentnavi">
			<span class="pages"><?php _e('Comment pages', 'twellow'); ?></span>
			<div id="commentpager">
				<?php echo $comment_pages; ?>
				<span id="cp_post_id"><?php echo $post->ID; ?></span>
			</div>
			<div class="fixed"></div>
		</div>
<?php
		}

?>

<?php endif; ?>

<div class="post-bottom-section">
	<h4>Leave a Reply</h4>
        
<?php if (!comments_open()) : // If comments are closed. ?>
	<div class="messagebox corner10">
		<span><?php _e('Comments are closed.', 'twellow'); ?></span>
	</div>
<?php elseif ( get_option('comment_registration') && !$user_ID ) : // If registration required and not logged in. ?>
	<div id="comment_login" class="messagebox">
		<?php
			if (function_exists('wp_login_url')) {
				$login_link = wp_login_url();
			} else {
				$login_link = get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink());
			}
		?>
		<?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'twellow'), $login_link); ?>
	</div>

<?php else : ?>

	
	
	<form id="commentform" method="post" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php">
		<div id="respond">

		<?php if ($user_ID) : ?>
			<?php
				if (function_exists('wp_logout_url')) {
					$logout_link = wp_logout_url();
				} else {
					$logout_link = get_option('siteurl') . '/wp-login.php?action=logout';
				}
			?>
			<p>
				<?php _e('Logged in as', 'twellow'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><strong><?php echo $user_identity; ?></strong></a>.
				 <a href="<?php echo $logout_link; ?>" title="<?php _e('Log out of this account', 'twellow'); ?>"><?php _e('Logout &raquo;', 'twellow'); ?></a>
			</p>

			<?php else : ?>
			<?php if ( $comment_author != "" ) : ?>
				<p>
					<?php printf(__('Welcome back <strong>%s</strong>.', 'twellow'), $comment_author) ?>
					
				</p>
			<?php endif; ?>

			<div id="authorinfo" class="floatleft">
            	<p>
                	<label class="small" for="author">Name (required)</label><br />
                    <input type="text" tabindex="1" size="24" value="" class="textfield" id="author" name="author">               
                </p>
                <p>
                	<label class="small" for="email">E-Mail (will not be published) (required)</label><br />
                    <input type="text" tabindex="2" size="24" value="" class="textfield" id="email" name="email">
                </p>
                <p>
                	<label class="small" for="url">Website</label><br />
                    <input type="text" tabindex="3" size="24" value="" class="textfield" id="url" name="url">
                        
                </p>
			</div>

			<?php if ( $comment_author != "" ) : ?>
				<script type="text/javascript">MGJS.setStyleDisplay('hide_author_info','none');MGJS.setStyleDisplay('author_info','none');</script>
			<?php endif; ?>

		<?php endif; ?>
	
		<!-- comment input -->
		<?php if ($user_ID) : ?>
		<p class="floatleft" style="width:100%;">
        	<label for="message">Your Message</label><br />
			<textarea cols="50" rows="8" tabindex="4" id="comment" name="comment" style="width:100%;"></textarea>
		</p>
        <?php else : ?>
		<p class="floatright">
        	<label for="message">Your Message</label><br />
			<textarea cols="50" rows="8" tabindex="4" id="comment" name="comment"></textarea>
		</p>
		<?php endif; ?>
		<div class="fix"></div>
        
        <!-- comment submit and rss -->
		<div id="submitbox">
			<a class="feed" href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Subscribe to comments feed', 'twellow'); ?></a>
			<div class="submitbutton">
				<input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e('Submit Comment', 'twellow'); ?>" />
			</div>
			<?php if (function_exists('highslide_emoticons')) : ?>
				<div id="emoticon"><?php highslide_emoticons(); ?></div>
			<?php endif; ?>
			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
			<div class="fix"></div>
		</div>

	</div>
	<?php do_action('comment_form', $post->ID); ?>
	</form>

	<?php if ($options['ctrlentry']) : ?>
		<script type="text/javascript">MGJS.loadCommentShortcut();</script>
	<?php endif; ?>

<?php endif; ?>

</div>
