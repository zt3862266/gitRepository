<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h1 class="post-title"><?php the_title(); ?></h1>

			<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			
			<div class="post-info">
				<?php the_time('F jS, Y') ?> in
				<?php the_category(', '); ?>
				<?php the_tags('| tags: ', ', ', ''); ?>
				<?php edit_post_link('[Edit this entry]','',''); ?>
			</div>
		</div>

		<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

	<?php endif; ?>

</div>
<div>&nbsp;&nbsp;&nbsp;&nbsp;分享到: 
<a target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&t=<?php the_title(''); ?>" class="facebook-share" title="Facebook">Facebook</a>
<a target="_blank" rel="nofollow" href="http://twitter.com/home/?status=<?php the_title(''); ?>:<?php the_permalink(); ?>" class="twitter-share" title="Twitter">Twitter</a>
<a target="_blank" rel="nofollow" href="http://del.icio.us/post?url=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" class="delicious-share" title="Delicious">Delicious</a>
<a target="_blank" rel="nofollow" href="http://www.kaixin001.com/repaste/share.php?rtitle=<?php the_title(''); ?>&rurl=<?php the_permalink(); ?>&rcontent=" class="kaixin001-share" title="开心网">开心网</a>
<a target="_blank" rel="nofollow" href="http://share.renren.com/share/buttonshare?link=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" class="renren-share" title="人人网">人人网</a>
<a target="_blank" rel="nofollow" href="http://www.douban.com/recommend/?url=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" class="douban-share" title="豆瓣">豆瓣</a>
<a target="_blank" rel="nofollow" href="http://www.xianguo.com/service/submitfav/?link=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" class="xianguo-share" title="鲜果">鲜果</a>
<a target="_blank" rel="nofollow" href="http://shuqian.qq.com/post?from=3&title=<?php the_title(''); ?>&uri=<?php the_permalink(); ?>" class="qqsq-share" title="QQ书签">QQ书签</a>
<a target="_blank" rel="nofollow" href="http://v.t.sina.com.cn/share/share.php?appkey=3408168656&url=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" class="sina-share" title="新浪微博">新浪微博</a>
<a target="_blank" rel="nofollow" href="http://v.t.qq.com/share/share.php?title=<?php the_title(''); ?>&url=<?php the_permalink(); ?>&appkey=3e87c17cc8ca4726b13a3167115dd13c&site=http://moesora.com/" class="tencent-share" title="腾讯微博">腾讯微博</a>
<a target="_blank" rel="nofollow" href="http://fanfou.com/sharer?u=<?php the_permalink(); ?>&t=<?php the_title(''); ?>" class="fanfou-share" title="饭否">饭否</a>
<a target="_blank" rel="nofollow" href="http://t.163.com/article/user/checkLogin.do?link=<?php the_permalink(); ?>&source=MoeSora.com&info=<?php the_title(''); ?> <?php the_permalink(); ?>" class="netease-share" title="网易微博">网易微博</a>
<a target="_blank" rel="nofollow" href="http://t.sohu.com/third/post.jsp?&url=<?php the_permalink(); ?>&title=<?php the_title(''); ?>&content=UTF-8" class="sohu-share" title="搜狐微博">搜狐微博</a>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
