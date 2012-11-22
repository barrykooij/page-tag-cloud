<?php
/**
* The PTC Widget
*/

class PTCWidget extends WP_Widget
{
  
  public function __construct()
  {
    $widget_ops = array( 'description' => __( "Displays page tags in a tagcloud") );
    parent::__construct('ptc_widget', __('Page Tag Cloud'), $widget_ops);
  }
  
  public function form($instance)
  {
    $settings = get_option($this->id);
    
    if($settings['title']=="")
    {
      $settings['title'] = "Tag Cloud";
      update_option($this->id, $settings);
    }
    
     echo "<p><label style='display:block;width:100px;'>Title</label><input name='wcttc[title]' type='text' value='{$settings['title']}' style='width:100%;' /></p>\n";
  }
  
  public function update($new_instance, $old_instance)
  {    
    $settings              = get_option($this->id);
    $settings['title']     = attribute_escape($_POST['wcttc']['title']);
    update_option($this->id, $settings);
    return $new_instance;  
  }
  
  public function widget($args, $instance)
  {
    echo $args['before_widget'];
    $this->generate_tag_cloud($args);
    echo $args['after_widget'];
  }
  
  private function generate_tag_cloud($args=array('before_title'=>'<h3>','after_title'=>'</h3>'))
  {
    global $post;
    wp_reset_query();
    $settings = get_option($this->id);
    $tags 		= wp_get_post_terms($post->ID , 'page_tags', array('orderby' => 'none'));
    if(count($tags)==0){return;}
    echo $args['before_title'] . $settings['title'] . $args['after_title']."\n";
    wp_tag_cloud(array('taxonomy' => 'page_tags', 'include' => implode(",", array_map(function($o){return $o->term_id;}, $tags))));
  }
  
}
?>