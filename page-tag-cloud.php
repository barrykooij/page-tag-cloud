<?php
/*
Plugin Name: Page Tag Cloud
Plugin URI: http://www.barrykooij.nl/page-tag-cloud
Description: Add tags to pages and display them in a tagcloud widget.
Version: 1.0.1
Author: Barry Kooij
Author URI: http://www.barrykooij.nl/
*/

require_once('widget.php');

class PageTagCloud
{
  
  public function __construct()
  {
    add_action("widgets_init", array(&$this, 'register_widget'));
    add_action("init", array(&$this, 'register_taxonomy'));
  }
  
  public function register_widget()
  {
    register_widget('PTCWidget'); 
  }
  
  public function register_taxonomy()
  {
    register_taxonomy( 'page_tags', 'page', array( 'hierarchical' => false, 'label' => 'Page Tags', 'show_ui' => true, 'query_var' => true, 'rewrite' => array( 'slug' => 'page-tag' ) ) );
  }

}

new PageTagCloud();
?>