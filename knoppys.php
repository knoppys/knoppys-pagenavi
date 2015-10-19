<?php
/*
	Plugin Name: Knoppys Page Navi
	Version: 3
	Plugin URI: http://www.knoppys.co.uk
	Description: THis plugin helps organise your page site navigation by providing your users with a hierarchical page navigation as a widget in your sidebar. 
	Author: Alex Knopp 
	Author URI: http://www.knoppys.co.uk
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


class knoppys_pagenav extends WP_Widget {

	// constructor
	function knoppys_pagenav() {
		parent::WP_Widget(false, $name = __('Knoppys Page Navi', 'wp_widget_plugin') );
	}

	// widget form creation
	function form($instance) {

	// Check values
	if( $instance) {
	     $title = esc_attr($instance['title']);
	} else {
	     $title = '';
	}
	?>

	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<?php
	}

	// update widget
	function update($new_instance, $old_instance) {
	      $instance = $old_instance;
	      // Fields
	      $instance['title'] = strip_tags($new_instance['title']);
	     return $instance;
	}

	// display widget
	function widget($args, $instance) {
	global $post;
	   extract( $args );
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   echo $before_widget;
	   // Display the widget
	   echo '<div class="widget-text wp_widget_plugin_box">';

	   // Check if title is set
	   if ( $title ) {
	      echo $before_title . $title . $after_title;
	   }

	   // wp_list_pages arguments. If the post has a post parent then the arguments are  
	   	if ($post->post_parent) {
			
			$args = array('child_of' => $post->post_parent, 'title_li' => __('<a href="' . get_permalink($post->post_parent) .'">' . get_the_title( $post->post_parent ) . '</a>'));
		
		} else {
			
			$args = array('child_of' => get_the_id(), 'title_li' => __(''));
		}

		//get the upper most parent title else show the title of the current page
		if ($post->post_parent) {
			
			$argst = get_the_title( $post->post_parent );
		
		} else {
			
			$argst = get_the_title();
		
		} 

		//check to see if this page has children or is a child, if it does or is, show the widget.
		if ( ($post->post_parent) || (get_pages(array( 'child_of' => $post->ID))) ) { ?>

			<h4 class="widget-title">
				<?php echo $argst; ?>			
			</h4>
			<ul>
				<?php wp_list_pages($args); ?>
			</ul>

		<?php } else {} 

	   
	   echo '</div>';
	   echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("knoppys_pagenav");'));
