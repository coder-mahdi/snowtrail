<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SnowTrail
 */

// Fallback function for get_field if ACF is not active
if (!function_exists('get_field')) {
  function get_field($field_name, $post_id = false) {
    if (!$post_id) {
      $post_id = get_the_ID();
    }
    return get_post_meta($post_id, $field_name, true);
  }
}

get_header();
?>

<main id="primary" class="site-main">
  <h1>Current Trail Status</h1>

  <ul>
    <?php
    $trails = get_posts([
      'post_type' => 'trail',
      'numberposts' => -1,
      'orderby' => 'title',
      'order' => 'ASC'
    ]);

    foreach ($trails as $trail) {
      $status = get_field('status', $trail->ID);
      $css_class = ($status === 'closed') ? 'closed' : 'open';
      echo '<li class="' . esc_attr($css_class) . '"><strong>' . esc_html($trail->post_title) . '</strong>: ' . esc_html(ucfirst($status)) . '</li>';
    }
    ?>
  </ul>

  <hr>

  <h2>Interactive Trail Map</h2>

  <div class="map-container" style="position: relative;" 
       data-trails='<?php echo json_encode(array_map(function($trail) {
         return ucfirst(get_field('status', $trail->ID));
       }, $trails), JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/trail-map.png" alt="Trail Map" class="trail-bg">
    <?php get_template_part('template-parts/trail-map'); ?>
    
    <!-- Tooltip -->
    <div id="tooltip" class="trail-tooltip">
      <span id="trail-tooltip-name"></span>: 
      <span id="trail-tooltip-status"></span>
    </div>
  </div>

  <style>
    .open { color: green; }
    .closed { color: red; }

    .trail-tooltip {
      position: absolute;
      display: none;
      background: #fff;
      color: #333;
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
      pointer-events: none;
      z-index: 1000;
      white-space: nowrap;
      margin-bottom: 5px;
    }

    .trail-tooltip::after {
      content: '';
      position: absolute;
      top: 100%;
      left: 50%;
      transform: translateX(-50%);
      border: 5px solid transparent;
      border-top-color: #fff;
      filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
    }

    .trail-tooltip .open {
      color: green;
    }

    .trail-tooltip .closed {
      color: red;
    }
  </style>

  <?php wp_enqueue_script('trail-map', get_template_directory_uri() . '/js/trail-map.js', array(), '1.0.0', true); ?>
</main><!-- #main -->

<?php
get_footer();
