<?php
/*
Plugin Name: People Grid
Plugin URI: http://wordpress.org/extend/plugins/#
Description: This is a plugin to create a custom post type to build a grid of people.
Author: Thomas Hobohm
Version: 1.0
Author URI: http://thomashobohm.com/
*/

// register custom post type on init
add_action('init', 'register_custom_posts_init');
add_action('acf/init', 'people_grid_add_local_field_groups');

function register_custom_posts_init() {
  $people_labels = array(
    'name' => 'People',
    'singular_name' => 'Person',
    'menu_name' => 'People'
  );
  $people_args = array(
    'labels' => $people_labels,
    'description' => 'An profile box',
    'public' => true,
    'capability_type' => 'post',
    'has_archive' => true,
    'supports' => array('title', 'thumbnail')
  );
  register_post_type('people', $people_args);
}

function people_grid_add_local_field_groups() {
  acf_add_local_field_group(array(
    'key' => 'people_1',
    'title' => 'People Info',
    'fields' => array(
      array(
        'key' => 'people_field_1',
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
          'value' => 'people'
        )
      )
    )
  ));
}

// [people_grid]
function people_grid_shortcode() {
  // Start output buffer
  ob_start();

  $args = array(
    'post_type' => 'people',
    'post_status' => 'publish',
    'posts_per_page' => '10'
  );
  $people_loop = new WP_Query($args);
  $number_of_columns = 3;
  $columns = array_fill(0, $number_of_columns, array());
  if ($people_loop->have_posts()) {
    $people_count = 0;
    while ($people_loop->have_posts()) {
      $people_loop->the_post();
      // Set Variables
      $name = get_the_title();
      $position = get_field('position');
      $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
      $person_link = get_the_permalink();
      $profile_image = $thumbnail[0];
      // Output
      array_push($columns[$people_count % $number_of_columns], array(
        'name' => $name,
        'position' => $position,
        'image' => $profile_image,
        'link' => $person_link
      ));
      $people_count++;
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
            // Build each people
            foreach($columns[$column] as $people) {
              $people_name = $people['name'];
              $people_position = $people['position'];
              $people_image_link = $people['image'];
              $people_link = $people['link'];
              ?>
                <a href="<?php echo $people_link; ?>">
                <div class="person">
                  <img src="<?php echo $people_image_link; ?>" alt="<?php echo $people_name; ?>">
                  <div class="info">
                    <h3><?php echo $people_name ?></h3>
                    <h3 class="subtitle"><?php echo $people_position ?></h3>
                  </div>
                </div>
                </a>
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

  $output = ob_get_contents();
  ob_end_clean();

  return $output;
}

wp_enqueue_style('myprefix-style', plugins_url('index.css', __FILE__));
add_shortcode('people_grid', 'people_grid_shortcode');
