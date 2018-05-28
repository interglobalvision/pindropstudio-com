<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php wp_title('|',true,'right'); bloginfo('name'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php
    get_template_part('partials/globie');
    get_template_part('partials/seo');
  ?>

  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
  <link rel="icon" href="<?php bloginfo('stylesheet_directory'); ?>/img/dist/favicon.png">
  <link rel="shortcut" href="<?php bloginfo('stylesheet_directory'); ?>/img/dist/favicon.ico">
  <link rel="apple-touch-icon" href="<?php bloginfo('stylesheet_directory'); ?>/img/dist/favicon-touch.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/img/dist/favicon.png">

  <?php if (is_singular() && pings_open(get_queried_object())) { ?>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
  <?php } ?>
  <?php wp_head(); ?>



</head>
<body <?php body_class(); ?>>
<!--[if lt IE 9]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p><![endif]-->

<section id="main-container">

  <header id="header" class="container">
    <nav id="menu-hamburger" class="mobile-only u-pointer">
      <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/hamburger.svg'); ?>
    </nav>
    <div class="grid-row">
      <div id="menu-item-logo" class="grid-item item-s-12 item-m-2">
        <a href="<?php echo home_url(); ?>">
          <h1 class="u-visuallyhidden"><?php bloginfo('name'); ?></h1>
          <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/pindrop-logo.svg'); ?>
        </a>
      </div>
      <nav id="menu-links" class="grid-item item-s-12 item-m-10">
        <ul id="menu" class="font-style-micro font-size-small u-inline-list">
          <li><a <?php echo menuActiveClasses('live', 'page'); ?>href="<?php echo home_url('live'); ?>">Live</a></li>
          <li><a <?php echo menuActiveClasses('sound-and-vision', 'page'); ?>href="<?php echo home_url('sound-and-vision'); ?>">Sound & Vision</a></li>
          <li><a <?php echo menuActiveClasses('luminaries', 'postype'); ?>href="<?php echo home_url('luminaries'); ?>">Luminaries</a></li>
           <li><a <?php echo menuActiveClasses('post', 'postype'); ?>href="<?php echo home_url('a-short-affairan-anthology-of-original-short-fictionpublished-12th-july-2018'); ?>">A Short Affair</a></li>
                   <li><a <?php echo menuActiveClasses('post', 'postype'); ?>href="<?php echo home_url('news'); ?>">News</a></li>
          <?php
            $shop_link = IGV_get_option('_igv_site_options', '_igv_shop_link');
            if ($shop_link) {
          ?>
          <li><a href="<?php echo $shop_link; ?>" target="_blank" rel="noopener">Shop</a></li>
          <?php
            }
          ?>
          <li><a <?php echo menuActiveClasses('submit', 'page'); ?>href="<?php echo home_url('submit'); ?>">Award</a></li>
          <li><a <?php echo menuActiveClasses('about', 'page'); ?>href="<?php echo home_url('about'); ?>">About</a></li>
          <li><a href="<?php echo home_url('members-only'); ?>">Members</a></li>
        </ul>
      </nav>
    </div>
  </header>
