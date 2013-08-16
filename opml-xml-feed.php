<?php
/*
Plugin Name: OPML XML Feed
Description: A custom OPML feed as per @LloydDavis.
Version: 1.0
Author: Martin Frost
Author URI: http://me.fronbow.co.uk
License: GPL
*/


function do_feed_opml() {
  load_template( dirname( __FILE__ ) . '/feed-template.php' );
}

add_action( 'do_feed_opml', 'do_feed_opml', 10, 1 );

function getUrls($string) {
  $regex = '/https?\:\/\/[^\" ]+/i';
  // <a href="http://link.lnk" title="" style="">text</a>
  
  preg_match_all($regex, $string, $matches);
  return ($matches);
}

function ret_urls(&$string) {
  $dom = new DOMDocument;
  $dom->loadHTML($string);
  $out = array();

  foreach ($dom->getElementsByTagName('a') as $node) {
    //echo $dom->saveHtml($node), PHP_EOL;
    $out[] = $dom->saveHtml($node);
    $node->removeAttribute('href');
  }
  $dom->saveHTML($string);

  return $out;
}

