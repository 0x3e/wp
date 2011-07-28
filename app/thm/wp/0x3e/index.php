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
<article><header><time datetime="<?php the_time(get_option('date_format')); ?>" pubdate><?php the_time(get_option('date_format')); ?></time><h1><?php the_title();?></h1></header><?php the_content();?>
</article>
<?php
}
get_footer();
