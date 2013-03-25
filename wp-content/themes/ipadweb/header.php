<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" />
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css" type="text/css" />
    <?php wp_head(); ?>
    <script type="text/javascript"><!--//--><![CDATA[//><!--

sfHover = function() {
	var sfEls = document.getElementById("nav").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);

    //--><!]]></script>
    <!--[if lt IE 7]>
        <script defer type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/pngfix.js"></script>
    <style type="text/css">
        *html .excrept_in {height: 1%;}
    </style>
    <![endif]-->
</head>
<body>
    <div id="header">
    <div id="topnav">
        <div id="topnav_left">
            <ul id="pagenav">
                <?php wp_list_pages('sort_column=menu_order&title_li=&depth=1'); ?>
            </ul>
        </div>
        
    <div class="clear"></div>
    </div><!-- End Topnav -->
        <div id="header_left">
            <h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
            <p><?php bloginfo('description'); ?></p>
        </div>
        <div id="header_right">
            <!--<div id="top_search">
                 <form id="searchform" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" value="Search This Site..." name="s" id="topsearch" onfocus="if (this.value == 'Search This Site...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search This Site...';}" />
                <input type="submit" id="searchbut" value="GO" /></form> 
            </div> -->
        </div>
    </div>
    <div id="navbar">
        <div id="navigation">
            <div id="nav_left">
                <ul id="nav">
                    <li><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('description'); ?>">Home</a></li>
                    <?php wp_list_categories('sort_column=name&title_li=&depth=3'); ?>
                </ul>
            </div>
            <div id="nav_right">
        <?php $tc_feed_rss_check = get_option('tc_feed_rss_check'); if($tc_feed_rss_check): ?>
                <a href="http://feeds.feedburner.com/<?php $tc_feed_rss = get_option('tc_feed_rss'); echo $tc_feed_rss; ?>" target="_blank">Subscribe to RSS</a>
                <a href="http://feeds.feedburner.com/<?php $tc_feed_rss = get_option('tc_feed_rss'); echo $tc_feed_rss; ?>" target="_blank"><img style="vertical-align:middle" src="<?php bloginfo('template_url'); ?>/images/rss.png" alt="Subscribe to <?php bloginfo('name'); ?>" /></a>
        <?php else: ?>
                <a href="<?php bloginfo('rss_url'); ?>" target="_blank">Subscribe to RSS</a>
                <a href="<?php bloginfo('rss_url'); ?>" target="_blank"><img style="vertical-align:middle" src="<?php bloginfo('template_url'); ?>/images/rss.png" alt="Subscribe to <?php bloginfo('name'); ?>" /></a>
        <?php endif; ?>
            </div>
        <div class="clear"></div>
        </div>
    </div><!-- Header End Here -->