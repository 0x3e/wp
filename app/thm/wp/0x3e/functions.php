<?php
Dev::log("info","theme functions.php");
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_filter ('the_content', 'wpautop');
