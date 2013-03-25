<?php global $wordPressThemeOptions;?>
<!-- sidebar -->
		<div id="sidebar" class="corner10TR">
        	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('east_sidebar') ) : ?>
        	<div id="search" class="corner10TR">
            	
    			<form id="quick-search" method="get" action="<?php bloginfo('home'); ?>">
        			<fieldset class="search">
						<label for="qsearch">Search:</label>
                        <input id="qsearch" type="text" class="tbox" name="s" value="<?php echo wp_specialchars($s, 1); ?>" title="Start typing and hit ENTER"/>
                        <input class="btn" type="submit" value="" />
                    </fieldset>
                </form>
                <p>You can search the archive here.</p>
                <div class="fix"></div>
                
			</div>
            
            
            <?php if (function_exists('get_calendar')) : ?>
            <div class="sidemenu">
            	<h3>Calendar</h3>
				<div id="calendar_wrap">
					<?php get_calendar(true); ?>
				</div>
			</div>
			<?php endif;?>		

			<div class="sidemenu">
                <h3>Archives</h3>
                <?php if(function_exists('wp_easyarchives_widget')) : ?>
					<?php wp_easyarchives_widget("mode=none&limit=6"); ?>
				<?php else : ?>
                	<ul>
                    	<?php wp_get_archives('type=monthly'); ?>
					</ul>
				<?php endif; ?>
            </div>
            
            
            <?php if($wordPressThemeOptions->option['showAdBox']=='yes') : ?>
            <div class="advertise">
				<h3>Advertise</h3>
                <ul>
                	<li>
                    	<a href="<?php echo ($wordPressThemeOptions->option['advertisment1Link']);?>">
                			<img src="<?php echo ($wordPressThemeOptions->option['advertisment1Image']);?>" width="125" height="125" alt="Advertisement 125 x 125" />
                    	</a>
                    </li>
                    <li>
                    	<a href="<?php echo ($wordPressThemeOptions->option['advertisment2Link']);?>">
                			<img src="<?php echo ($wordPressThemeOptions->option['advertisment2Image']);?>" width="125" height="125" alt="Advertisement 125 x 125" />
                    	</a>
                    </li>
                    <li>
                    	<a href="<?php echo ($wordPressThemeOptions->option['advertisment3Link']);?>">
                			<img src="<?php echo ($wordPressThemeOptions->option['advertisment3Image']);?>" width="125" height="125" alt="Advertisement 125 x 125" />
                    	</a>
                    </li>
                    <li>
                    	<a href="<?php echo ($wordPressThemeOptions->option['advertisment4Link']);?>">
                			<img src="<?php echo ($wordPressThemeOptions->option['advertisment4Image']);?>" width="125" height="125" alt="Advertisement 125 x 125" />
                    	</a>
                    </li>
                   
                    
                </ul>
                <div class="fix"></div>
            </div>
            <?php endif; ?>
            
         <?php if (function_exists('wp_tag_cloud')) : ?>
			<div class="sidemenu">
				<h3>Tag Cloud</h3>
                <div id="tagcloud">
					<?php wp_tag_cloud('smallest=8&largest=16'); ?>
                </div>
            </div>
         <?php endif;?>
         

         <div class="popular">

				<h3>Most Popular</h3>
				<ul>
			   		<?php wpListPopularPosts($wordPressThemeOptions->option['popularPostNumber']);?>
                </ul>

			</div>
            <?php endif;?>

      <!-- /sidebar -->