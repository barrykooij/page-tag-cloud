<?php
/*
Plugin Name: Page Tag Cloud
Plugin URI: http://www.barrykooij.com/page-tag-cloud/
Description: Add tags to pages and display them in a tag cloud widget.
Version: 1.1.1
Author: Barry Kooij
Author URI: http://www.barrykooij.com/
*/

require_once('widget.php');

class PageTagCloud
{
  
  public function __construct()
  {
    add_action( 'widgets_init', array( $this, 'register_widget' ) );
    add_action( 'init', array( $this, 'register_taxonomy' ) );

		add_action( 'page_tags_add_form_fields', 	array( $this, 'add_homepage_tag_field' 	), 10, 2 );
		add_action( 'page_tags_edit_form_fields', array( $this, 'edit_homepage_tag_field' ), 10, 2 );

		add_action( 'edited_page_tags', array( $this, 'save_taxonomy_custom_meta' ), 10, 2 );
		add_action( 'create_page_tags', array( $this, 'save_taxonomy_custom_meta' ), 10, 2 );
	}

  public function register_widget()
  {
    register_widget( 'PTCWidget' );
  }
  
  public function register_taxonomy()
  {
    register_taxonomy( 'page_tags', 'page', array( 'hierarchical' => false, 'label' => 'Page Tags', 'show_ui' => true, 'query_var' => true, 'rewrite' => array( 'slug' => 'page-tag' ) ) );
  }

	public function add_homepage_tag_field()
	{
	?>
		<div class="form-field">
        <input type="checkbox" name="show_on_home" id="show_on_home" value="1" style="float:left; width: auto; margin-right: 5px; margin-top:3px" />
				<label for="show_on_home"><?php _e( 'Show on home' ); ?></label>
				<p class="description"><?php _e( 'Display tag on home page which displays my latest posts.' ); ?></p>
		</div>
	<?php
	}

	public function edit_homepage_tag_field($term)
	{
		$home_tags = get_option( 'ptc_home_tags' , array() );
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="show_on_home"><?php _e( 'Show on home' ); ?></label></th>
			<td>
        <input type="checkbox" name="show_on_home" id="show_on_home" value="1"<?php echo ( ( in_array( $term->term_id, $home_tags ) ) ? 'checked="checked"' : '' ); ?> />
				<p class="description"><?php _e( 'Display tag on home page which displays my latest posts.' ); ?></p>
			</td>
		</tr>
		<?php
	}

	public function save_taxonomy_custom_meta( $term_id )
	{

		$home_tags 	= get_option( 'ptc_home_tags' , array() );

		if( isset( $_POST[ 'show_on_home' ] ) ) {
			if( !in_array( $term_id, $home_tags ) ) {
				$home_tags[] = $term_id;
			}
		}else {
			unset( $home_tags[ $term_id ] );
		}

		update_option( 'ptc_home_tags' , $home_tags );
	}

}

add_action( 'plugins_loaded', create_function( '', 'new PageTagCloud();' ) );