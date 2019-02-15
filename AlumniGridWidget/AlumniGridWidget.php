<?php
/*
Plugin Name: Alumni Grid
Plugin URI: http://wordpress.org/extend/plugins/#
Description: This is a plugin to create a grid of alumni profiles.
Author: Thomas Hobohm
Version: 0.1
Author URI: http://thomashobohm.com/
*/

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
    $alumni_names = empty($instance['alumni_names']) ? array() : $instance['alumni_names'];
    $alumni_image_links = empty($instance['alumni_image_links']) ? array() : $instance['alumni_image_links'];
    $alumni_positions = empty($instance['alumni_positions']) ? array() : $instance['alumni_positions'];
    $alumni_length = max(array_keys($alumni_names))+1;
    ?>
    <div class="alumni">
      <?php foreach($alumni_names as $alumni_index=>$alumni_value) { ?>
        <div class="alum">
          <h2><?php echo $alumni_names[$alumni_index]; ?></h2>
          <div class="name">
            <label for="<?php echo esc_attr($this->get_field_id('alumni_names')); ?>[<?php echo $alumni_index; ?>]">Name</label>
            <input
              type="text"
              name="<?php echo esc_attr($this->get_field_name('alumni_names')); ?>[<?php echo $alumni_index; ?>]"
              value="<?php echo $alumni_names[$alumni_index]; ?>">
          </div>
          <div class="position">
            <label for="<?php echo esc_attr($this->get_field_id('alumni_positions')); ?>[<?php echo $alumni_index; ?>]">Position</label>
            <input
              type="text"
              name="<?php echo esc_attr($this->get_field_name('alumni_positions')); ?>[<?php echo $alumni_index; ?>]"
              value="<?php echo $alumni_positions[$alumni_index]; ?>">
          </div>
          <div class="image">
            <label for="<?php echo esc_attr($this->get_field_id('alumni_image_links')); ?>[<?php echo $alumni_index; ?>]">Image Link</label>
            <input
              type="text"
              name="<?php echo esc_attr($this->get_field_name('alumni_image_links')); ?>[<?php echo $alumni_index; ?>]"
              value="<?php echo $alumni_image_links[$alumni_index]; ?>">
          </div>
          <div class="delete">
            <label for="<?php echo esc_attr($this->get_field_id('delete_alumni')); ?>[<?php echo $alumni_index; ?>]">Delete?</label>
            <input
              type="checkbox"
              name="<?php echo esc_attr($this->get_field_name('delete_alumni')); ?>[<?php echo $alumni_index; ?>]"
              value="<?php echo $alumni_index; ?>" />
          </div>
        </div>
      <?php } ?>
      <div class="alum">
        <h2>New Alumni</h2>
        <div class="name">
          <label for="<?php echo esc_attr($this->get_field_id('alumni_names')); ?>[<?php echo $alumni_length; ?>]">Name</label>
          <input
            type="text"
            name="<?php echo esc_attr($this->get_field_name('alumni_names')); ?>[<?php echo $alumni_length; ?>]"
            value="">
        </div>
        <div class="position">
          <label for="<?php echo esc_attr($this->get_field_id('alumni_positions')); ?>[<?php echo $alumni_length; ?>]">Position</label>
          <input
            type="text"
            name="<?php echo esc_attr($this->get_field_name('alumni_positions')); ?>[<?php echo $alumni_length; ?>]"
            value="">
        </div>
        <div class="image">
          <label for="<?php echo esc_attr($this->get_field_id('alumni_image_links')); ?>[<?php echo $alumni_length; ?>]">Image Link</label>
          <input
            type="text"
            name="<?php echo esc_attr($this->get_field_name('alumni_image_links')); ?>[<?php echo $alumni_length; ?>]"
            value="">
        </div>
      </div>
    </div>
    <?php
  }

  // save options
  public function update($new_instance, $old_instance) {
    $instance = array();
    $alumni_names = empty($new_instance['alumni_names']) ? array() : (array) $new_instance['alumni_names'];
    $alumni_positions = empty($new_instance['alumni_positions']) ? array() : (array) $new_instance['alumni_positions'];
    $alumni_image_links = empty($new_instance['alumni_image_links']) ? array() : (array) $new_instance['alumni_image_links'];
    $delete_alumni = empty($new_instance['delete_alumni']) ? array() : (array) $new_instance['delete_alumni'];
    $alumnis_to_remove = array();
    foreach ($delete_alumni as $alumni_index) {
      array_push($alumnis_to_remove, (int)$alumni_index);
    }
    foreach ($alumni_names as $alumni_index=>$alumni_name) {
      if ($alumni_name == '') {
        if (!in_array($alumni_index, $alumnis_to_remove)) {
          array_push($alumnis_to_remove, $alumni_index);
        }
      }
    }
    $instance['alumni_names'] = array_filter($alumni_names, function($key) use ($alumnis_to_remove) {
      return !in_array($key, $alumnis_to_remove);
    }, ARRAY_FILTER_USE_KEY);
    $instance['alumni_positions'] = array_filter($alumni_positions, function($key) use ($alumnis_to_remove) {
      return !in_array($key, $alumnis_to_remove);
    }, ARRAY_FILTER_USE_KEY);
    $instance['alumni_image_links'] = array_filter($alumni_image_links, function($key) use ($alumnis_to_remove) {
      return !in_array($key, $alumnis_to_remove);
    }, ARRAY_FILTER_USE_KEY);

    return $instance;
  }
}

// register AlumniGridWidget
add_action('widgets_init', function() {
  register_widget('AlumniGridWidget');
});
