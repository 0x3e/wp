<?php 
Dev::log("info","theme index.php");
get_header();
$i=0;
while ( have_posts() )
{ 
  $i++;
  the_post();
  //Dev::log_print("post",$GLOBALS['post']);
  Dev::log("info","post $i");
  the_title();
  the_content();
}
get_footer();
