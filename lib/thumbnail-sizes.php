<?php

if( function_exists( 'add_theme_support' ) ) {
  add_theme_support( 'post-thumbnails' );
}

if( function_exists( 'add_image_size' ) ) {
  add_image_size( 'admin-thumb', 150, 150, false );
  add_image_size( 'opengraph', 1200, 630, true );

  add_image_size( 'l-3', 350, 340, false ); // 350 @ .970873786 = 339.8058251
  add_image_size( 'l-3-square', 350, 350, true );

  add_image_size( 'l-4', 467, 504, false ); // 467 @ 1.079136691 = 503.956834697
  add_image_size( 'l-4-square', 467, 467, true );
  add_image_size( 'l-4-landscape', 467, 311, true ); // 467 @ .666666667 = 311.33333333
  add_image_size( 'l-4-full', 467, 9999, false );
  add_image_size( 'l-4-tall-ad', 467, 320, false );

  add_image_size( 'l-5-square', 542, 542, true ); // 584

  add_image_size( 'l-6', 700, 484, false ); // 700 @ .692307692 = 484.6153844
  add_image_size( 'l-6-square', 700, 700, true );

  add_image_size( 'l-8', 934, 539, false ); // 934 @ .577367206 = 539.260970404
  add_image_size( 'l-8-square', 934, 934, true );
  add_image_size( 'l-8-full', 934, 9999, false );

  add_image_size( 'lightbox', 1030, 644, false );

  add_image_size( 'l-12-wide-ad', 1384, 269, true ); // 1384 @ .194615385 = 269.34769284
  add_image_size( 'l-12-carousel', 1384, 506, true ); // 1384 @ .366043614 = 506.604361776
}
