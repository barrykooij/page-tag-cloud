<?php

/**
 * The Widget
 */
class PTCWidget extends WP_Widget {

	private $default_title = 'Tag Cloud';

	/**
	 * __construct
	 */
	public function __construct() {
		$widget_ops = array( 'description' => __( 'Displays page tags in a tagcloud' ) );
		parent::__construct( 'ptc_widget', __( 'Page Tag Cloud' ), $widget_ops );
	}

	/**
	 * Widget form
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$title = $this->default_title;
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}
		echo "<p><label for='" . $this->get_field_id( 'title' ) . "' style='display:block;width:100px;'>Title</label>";
		echo "<input type='text' name='" . $this->get_field_name( 'title' ) . "' id='" . $this->get_field_id( 'title' ) . "' value='" . esc_attr( $title ) . "' style='width:100%;' /></p>\n";
	}

	/**
	 * Update action
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = $new_instance['title'];

		return $instance;
	}

	/**
	 * Widget output
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$this->generate_tag_cloud( $args, $instance );
		echo $args['after_widget'];
	}

	/**
	 * Map terms
	 *
	 * @param $o
	 *
	 * @return int
	 */
	public function map_terms( $o ) {
		return $o->term_id;
	}

	/**
	 * Generate the actual tag cloud
	 *
	 * @param $args
	 * @param $instance
	 */
	private function generate_tag_cloud( $args, $instance ) {
		global $post;

		// set defaults
		$args = wp_parse_args( $args, array( 'before_title' => '<h3>', 'after_title' => '</h3>' ) );

		wp_reset_query();

		$title = $this->default_title;
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		}


		$tags = array();
		if ( is_front_page() && get_option( 'show_on_front' ) == 'posts' ) {
			$home_tags = get_option( 'ptc_home_tags', array() );
			$tags      = get_terms( 'page_tags', array(
				'include'    => $home_tags,
				'hide_empty' => false,
				'orderby'    => 'none'
			) );
		} else if ( isset( $post ) ) {
			$tags = wp_get_post_terms( $post->ID, 'page_tags', array( 'hide_empty' => false, 'orderby' => 'none' ) );
		}

		if ( count( $tags ) == 0 ) {
			return;
		}
		echo $args['before_title'] . $title . $args['after_title'] . "\n";
		wp_tag_cloud( array(
			'taxonomy'   => 'page_tags',
			'hide_empty' => false,
			'include'    => implode( ",", array_map( array( $this, 'map_terms' ), $tags ) )
		) );
	}

}