<?php global $wordPressThemeOptions;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<meta name="author" content="Dalih Susilo - fauzy.info" />
<meta name="description" content="Free Wordpress Themes" />
<meta name="keywords" content="Free, Wordpress, Themes, Mac, Apple" />

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); echo('/');?>plugins/mooflow/mooflow-min.css" type="text/css" media="screen" charset="utf-8" />
<script type="text/javascript" src="<?php bloginfo('template_url'); echo('/');?>js/curvycorners-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); echo('/');?>js/mootools-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); echo('/');?>js/jquery-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); echo('/');?>js/stack-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); echo('/');?>js/menu-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); echo('/');?>js/corner-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); echo('/');?>js/comment-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); echo('/');?>js/base-min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php bloginfo('template_url'); echo('/');?>plugins/mooflow/mooflow-min.js"></script>
<script type="text/javascript">
	$.noConflict();  
</script>
<?php wp_head(); ?>
</head>
<?php flush(); ?>
<body id="body">
<div id="wrapper">
<!--header -->
<div id="pagemenu-wrap">
    <div id="pagemenu">
    
   
	<!-- BEGIN Menu -->
    <ul id="navigationpagemenu" class="navigation">
		<li><a id="home-up" class="parent" href="<?php bloginfo('url'); ?>" title="Home"></a></li>
		<?php 
		$args = array(
			'title_li'     => 0,
			'sort_column'  => 'menu_order',
			'link_before'  => '<span>',
			'link_after'   => '</span>'
		); 
		wp_list_pages($args);
		?>
    </ul>
	<!-- END pagemenu -->	
    </div>
<!-- END pagemenu-wrap -->
</div>
<!--header -->
<div id="header-wrap">
	<div id="header">   
    
    <div id="container1" class="floatleft">
    	<img id="logo" class="floatleft" src="<?php echo ($wordPressThemeOptions->option['logo']);?>" />
        <div class="floatright">
           	<h1 id="title" class="floatleft"><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
            <div class="fix"></div>
           	<h4 id="description" class="floatleft"><?php bloginfo('description');?></h4>
		</div>            	
	</div>
    
	<div class="fix"></div>

<!--/header-->
</div>
	<div id="head-categories" class="corner10">
    	<ul id="breadcrumbs">
			<a id="home" title="Go to homepage" href="<?php echo get_settings('home'); ?>/"></a>
            <?php if (is_single()) : ?>
            <span><?php the_category(' '); ?></span> 
            <?php endif; ?>	
            <?php if (!is_home()) : ?>
            <span id="title"> <?php the_title(); ?></span>
            <?php endif; ?>	
        </ul>
       
        <div id="dropdown-categories" class="corner10TBR">
        <p class="corner10TBR">Browse Categories</p>
        
		</div><!-- #dropdown-categories -->
        
	</div>
    <?php if (function_exists('wp_list_cats')) : ?>
    <div id="contaner-navigation-category">
	<ul id="navigationcategory">
    	<?php wp_list_cats('sort_column=name'); ?>
        <div class="fix"></div>
    </ul>
    </div>
    <?php endif; ?>	

</div>
	
<!-- content-outer -->
<div id="content-wrap" class="clear" >
	<?php if (is_home()) : ?>
		<?php if (function_exists('wpFeaturedPost')) : ?>
        <div id="feature" class="corner10"> 
            <div id="mooflow">
                <?php wpFeaturedPost($wordPressThemeOptions->option['featuredNumber']);?>
            </div>
        </div>
        <?php endif; ?>	
    <?php endif; ?>	
	<!-- content -->
    <div id="container-search-archive">
    	<div id="search-archive"></div> 
    </div> 
	<div id="content" class="corner10">