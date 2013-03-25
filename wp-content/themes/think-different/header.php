<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
<!-- MS Meta -->
<meta http-equiv="imagetoolbar" content="no" />
<meta name="MSSmartTagsPreventParsing" content="true" />

<!-- Stylesheet Info -->
<style type="text/css" media="screen">
<!--
@import url(<?php bloginfo('stylesheet_url'); ?>);
-->
</style>

<!-- Image Styles -->
<style type="text/css">
<!--
#wrap {background: #fff url(<?php bloginfo('template_url'); ?>/images/wrapbg.jpg) repeat-y;}
#header{background: #fff url(<?php bloginfo('template_url'); ?>/images/header.jpg);}
#footer {background: #fff url(<?php bloginfo('template_url'); ?>/images/footerbg.gif) repeat;}
-->
</style>

<!-- RSS Feed -->
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />

<!-- Atom Feed -->
<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />

<!-- Archives -->
<?php wp_get_archives('type=monthly&format=link'); ?>

<!-- Pingback -->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!-- Wordpress Headers -->
<meta name="designer" content="Teli Design www.telidesign.com" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /><!-- leave for stats -->
<?php wp_head(); ?>
</head>

<body>
<div id="wrap">
<div id="header">
<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
<p class="tagline"><?php bloginfo('description'); ?></p>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
</div>