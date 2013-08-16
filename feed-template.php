<?php


header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Thu, 01-Jan-70 00:00:01 GMT');


$more = 1;
$nl = "\n";
$outline = array();
$c = 0;
$d = 0;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'><opml version="2.0">'.$nl;

//manipulate the data first, then display after we've translated everything!
while (have_posts()) {
  the_post();

  $outline[$c]['title']     = get_the_title();
  //$outline[$c]['body']      = get_the_content();
  //need to split body up by tags
  $dom = new DOMDocument;
  $dom->loadHTML(get_the_content());
  $links = array();
  
  foreach ($dom->getElementsByTagName('a') as $node) {
    $links[$d]['node'] = $dom->saveHtml($node);

    $links[$d]['href'] = $node->getAttribute('href');
    $links[$d]['title'] = $node->getAttribute('title');
    
    $node->removeAttribute('href');
    $d++;
  }
  $outline[$c]['body'] = $dom->saveHTML($node);
  $outline[$c]['links'] = $links;

  //$outline[$c]['body'] = $dom;
  
  //------------------------------
  $outline[$c]['author']    = get_the_author();
  $outline[$c]['authormail']= get_the_author_email();
  $outline[$c]['author_id'] = get_the_author_ID();
  $outline[$c]['post_id']   = get_the_ID();
  

  $c++;
}

?>
<head>
  <title>WP OPML export - <?php echo date('Y-m-d'); ?></title>
  <ownerName><?php echo $outline[0]['author']; ?></ownerName>
  <ownerEmail><?php echo $outline[0]['authormail']; ?></ownerEmail>
  <dateCreated><?php echo date(DATE_RFC822); ?></dateCreated>
  <expansionState><?php echo ''; ?></expansionState>
  <docs>http://dev.opml.org/spec2.html</docs>
</head>
<body>
  <?php
  foreach ($outline as $content):
  ?>
  <outline type="outline" text="<?php echo $outline[0]['title']; ?>" />
  <outline type="outline" text="<?php echo strip_tags($outline[0]['body']); ?>" />
  <?php
  foreach ($content['links'] as $link_out):
  ?><outline type="link" text="<?php echo $link_out['title']; ?>" url="<?php echo $link_out['href']; ?>" />
  <?php
  endforeach;

  //print_r($outline);
  endforeach;
  ?>
</body>
</opml>
