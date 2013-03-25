	<div id="sidebar">
		<ul>

			<!--
			<li><h2>Author</h2>
			<p>Brief author overview.</p>
			</li>
			-->

			<?php wp_list_pages('title_li=<h2>Pages</h2>' ); ?>
			
			<li><h2>Recent Entries</h2>
				<ul>
					<?php query_posts("showposts=10"); ?>
					<?php while (have_posts()) : the_post(); ?>
					<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
					<?php endwhile; ?>
				</ul>			
			</li>

			<li><h2>Archives</h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<li><h2>Categories</h2>
				<ul>
				<?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
				</ul>
			</li>

			<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>				
				<?php get_links_list(); } ?>
				
				<li><h2>Feeds</h2>
					<ul>
					<li><a href="<?php bloginfo('rss2_url'); ?>">Entries Feed</a></li>
					<li><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments Feed</a></li>
					</ul>
				</li>
				<li><h2>Meta</h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
				</li>
			
		</ul>
	</div>

