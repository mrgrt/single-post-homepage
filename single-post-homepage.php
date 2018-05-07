<?php
/*
Plugin Name: Single Post homepage
Plugin URI: https://github.com/miya0001/oembed-gist
Description: Use a single post for your site's front page.
Author: Grahame Thomson
Version: 1.0
Author URI: http://www.grahamethomson.com
*/



function add_pages_to_dropdown( $pages, $r ){
  if ( ! isset( $r[ 'name' ] ) )
  return $pages;

  if ( 'page_on_front' == $r[ 'name' ] ) {
    $args = array(
      'post_type' => 'post'
    );

    $posts = get_posts( $args );
    $pages = array_merge( $pages, $posts );
  }

  return $pages;
}
add_filter( 'get_pages', 'add_pages_to_dropdown', 10, 2 );




add_action( 'pre_get_posts', function ( $q )
{

  // Check to see if static page is set for the homepage.
  if (!is_admin() && $q->is_main_query() && 'page' === get_option( 'show_on_front' )) {

    //Determine if current post is homepage
    if($q->get("page_id") == get_option("page_on_front")){

      // Check the post type of the id that is set as the front page.
      $front_page_post_type =  get_post_type($q->get("page_id"));

      //Stop redirect to the single post.
      $q->set( 'post_type', $front_page_post_type);

      // Use the single post template (single.php) instead of page.php
      // front-page.php still works with this to overwrite either.
      if($front_page_post_type!="page"){

        $q->is_single = true;

      }

    }

  }
});
