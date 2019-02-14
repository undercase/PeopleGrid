<?php
/*
Plugin Name: Alumni Grid
Plugin URI: http://wordpress.org/extend/plugins/#
Description: This is a plugin to create a grid of alumni profiles.
Author: Thomas Hobohm
Version: 0.1
Author URI: http://thomashobohm.com/
*/

function empty_filter($text) {
  return $text != '';
}

class AlumniGridWidget extends WP_Widget {
  // class constructor
  public function __construct() {
    $widget_ops = array(
      'classname' => 'AlumniGridWidget',
      'description' => 'A plugin to create a grid of alumni profiles',
    );

    parent::__construct('AlumniGridWidget', 'Alumni Grid Widget', $widget_ops);
  }

  // output the widget content on the frontend
  public function widget($args, $instance) {}

  // output the option form field in admin Widgets screen
  public function form($instance) {
    $alumni = empty($instance['alumni']) ? array() : $instance['alumni'];
    $alumnilength = count($alumni);
    ?>
    <ul>
    <?php foreach ($alumni as $alumindex=>$alum) { ?>
      <li>
        <input
          type="text"
          name="<?php echo esc_attr($this->get_field_name('alumni')); ?>[<?php echo $alumindex; ?>]"
          value="<?php echo $alum; ?>">
      </li>
    <?php } ?>
    <li>
      <input
        type="text"
        name="<?php echo esc_attr($this->get_field_name('alumni')); ?>[<?php echo $alumnilength; ?>]"
        value="">
    </li>
    </ul>
    <?php
  }

  // save options
  public function update($new_instance, $old_instance) {
    $instance = array();
    $alumni = empty($new_instance['alumni']) ? array() : (array) $new_instance['alumni'];
    $instance['alumni'] = array_filter(array_map('sanitize_text_field', $alumni), 'empty_filter');

    return $instance;
  }
}

// register AlumniGridWidget
add_action('widgets_init', function() {
  register_widget('AlumniGridWidget');
});
