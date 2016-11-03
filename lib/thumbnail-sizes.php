<?php

if( function_exists( 'add_theme_support' ) ) {
  add_theme_support( 'post-thumbnails' );
}

if( function_exists( 'add_image_size' ) ) {
  add_image_size( 'admin-thumb', 150, 150, false );
  add_image_size( 'opengraph', 1200, 630, true );

  add_image_size( 'l-3', 309, 9999, false );
  add_image_size( 'l-3-square', 309, 309, true );

  add_image_size( 'l-4', 417, 9999, false );
  add_image_size( 'l-4-square', 417, 417, true );
  add_image_size( 'l-4-landscape', 417, 278, true );

  add_image_size( 'l-6', 650, 9999, false );
  add_image_size( 'l-6-square', 650, 650, true );

  add_image_size( 'l-8', 866, 9999, false );
  add_image_size( 'l-8-square', 866, 866, true );

  add_image_size( 'gallery', 1200, 9999, false );
}