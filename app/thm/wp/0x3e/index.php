<?php 
Dev::log("info","theme index.php");
get_header();
$i=0;
while(have_posts())
{ 
  $i++;
  the_post();
  //Dev::log_print("post",$GLOBALS['post']);
  Dev::log("info","post $i");
?>
<h2><?php the_title();?></h2>
<?php the_content();?>
<?php
}
get_footer();
