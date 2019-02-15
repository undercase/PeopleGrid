<?php
/*
Plugin Name: Alumni Grid Custom Post Type
Plugin URI: http://wordpress.org/extend/plugins/#
Description: This is a plugin to create a custom post type to build a grid of alumni profiles.
Author: Thomas Hobohm
Version: 0.1
Author URI: http://thomashobohm.com/
*/

// register custom post type on init
add_action('init', 'register_custom_posts_init');
add_action('acf/init', 'alumni_grid_add_local_field_groups');

function register_custom_posts_init() {
  $alumni_labels = array(
    'name' => 'Alumni',
    'singular_name' => 'Alum',
    'menu_name' => 'Alumni'
  );
  $alumni_args = array(
    'labels' => $alumni_labels,
    'description' => 'An alumni profile box',
    'public' => true,
    'capability_type' => 'post',
    'has_archive' => true,
    'supports' => array('title', 'thumbnail')
  );
  register_post_type('alumni', $alumni_args);
}

function alumni_grid_add_local_field_groups() {
  acf_add_local_field_group(array(
    'key' => 'alumni_1',
    'title' => 'Alumni Info',
    'fields' => array(
      array(
        'key' => 'alumni_field_1',
        'label' => 'Position',
        'name' => 'position',
        'type' => 'text'
      )
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'alumni'
        )
      )
    )
  ));
}

// [alumni_grid]
function alumni_grid_shortcode() {
  $args = array(
    'post_type' => 'alumni',
    'post_status' => 'publish',
    'posts_per_page' => '10'
  );
  $alumni_loop = new WP_Query($args);
  $number_of_columns = 3;
  $columns = array_fill(0, $number_of_columns, array());
  if ($alumni_loop->have_posts()) {
    $alumni_count = 0;
    while ($alumni_loop->have_posts()) {
      $alumni_loop->the_post();
      // Set Variables
      $name = get_the_title();
      $position = get_field('position');
      $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
      $profile_image = $thumbnail[0];
      // Output
      array_push($columns[$alumni_count % $number_of_columns], array(
        'name' => $name,
        'position' => $position,
        'image' => $profile_image
      ));
      $alumni_count++;
    }
  }
  ?>
  <div class="wrapper">
    <div class="grid">
      <?php
      // Build each column
      for($column=0; $column<$number_of_columns; $column++) {
        ?>
          <div class="col">
            <?php
            // Build each alumni
            foreach($columns[$column] as $alumni) {
              $alumni_name = $alumni['name'];
              $alumni_position = $alumni['position'];
              $alumni_image_link = $alumni['image'];
              ?>
                <div class="person">
                  <img src="<?php echo $alumni_image_link; ?>" alt="<?php echo $alumni_name; ?>">
                  <div class="info">
                    <h3><?php echo $alumni_name ?></h3>
                    <h3 class="subtitle"><?php echo $alumni_position ?></h3>
                  </div>
                </div>
              <?php
            }
            ?>
          </div>
        <?php
      }
      ?>
    </div>
  </div>
  <?php
}

add_shortcode('alumni_grid', 'alumni_grid_shortcode');
