<?php
/**
 * Template Name: Snow Report Output
 * 
 * This file displays the snow trail status
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
  <div class="trail-status">
    <h1>Current Trail Status</h1>

    <ul>
      <?php
      $trails = get_posts([
        'post_type' => 'trail',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
      ]);

      // Separate open and closed trails
      $open_trails = [];
      $closed_trails = [];

      foreach ($trails as $trail) {
        $status = get_field('status', $trail->ID);
        if ($status === 'open') {
          $open_trails[] = $trail;
        } else {
          $closed_trails[] = $trail;
        }
      }

      // Sort open trails alphabetically
      usort($open_trails, function($a, $b) {
        return strcmp($a->post_title, $b->post_title);
      });

      // Sort closed trails alphabetically
      usort($closed_trails, function($a, $b) {
        return strcmp($a->post_title, $b->post_title);
      });

      // Display open trails first
      foreach ($open_trails as $trail) {
        $status = get_field('status', $trail->ID);
        $css_class = ($status === 'closed') ? 'closed' : 'open';
        echo '<li class="' . esc_attr($css_class) . '">';
        echo '<strong>' . esc_html($trail->post_title) . '</strong>';
        echo '<span>' . esc_html(ucfirst($status)) . '</span>';
        echo '</li>';
      }

      // Display closed trails second
      foreach ($closed_trails as $trail) {
        $status = get_field('status', $trail->ID);
        $css_class = ($status === 'closed') ? 'closed' : 'open';
        echo '<li class="' . esc_attr($css_class) . '">';
        echo '<strong>' . esc_html($trail->post_title) . '</strong>';
        echo '<span>' . esc_html(ucfirst($status)) . '</span>';
        echo '</li>';
      }
      ?>
    </ul>
  </div>

  <hr>

  <div class="interactive-trail-map">
    <h2>Interactive Trail Map</h2>

    <div class="map-container" style="position: relative;" 
         data-trails='<?php echo json_encode(array_reduce($trails, function($carry, $trail) {
           $carry[$trail->post_title] = ucfirst(get_field('status', $trail->ID));
           return $carry;
         }, []), JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
      <img src="<?php echo get_template_directory_uri(); ?>/assets/trail-map.png" alt="Trail Map" class="trail-bg">
      <?php get_template_part('template-parts/trail-map'); ?>
      
      <!-- Tooltip -->
      <div id="tooltip" class="trail-tooltip">
        <span id="trail-tooltip-name"></span>: 
        <span id="trail-tooltip-status"></span>
      </div>
    </div>
  </div>

  <?php wp_enqueue_script('trail-map', get_template_directory_uri() . '/js/trail-map.js', array(), '1.0.0', true); ?>
</main><!-- #main -->

<?php
get_footer();
