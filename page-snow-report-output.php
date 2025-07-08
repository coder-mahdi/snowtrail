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
      echo "<li class='$css_class'><strong>{$trail->post_title}</strong>: " . ucfirst($status) . "</li>";
    }
    ?>
  </ul>

  <hr>

  <h2>Interactive Trail Map</h2>

  <div class="map-container">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/trail-map.png" alt="Trail Map" class="trail-bg">
    <?php get_template_part('template-parts/trail-map'); ?>
    <div id="tooltip"></div>
  </div>

  <style>
    .open { color: green; }
    .closed { color: red; }
  </style>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const shapes = document.querySelectorAll('polygon[data-trail]');
      const tooltip = document.getElementById('tooltip');

      const trailData = {
        <?php
        foreach ($trails as $trail) {
          $status = get_field('status', $trail->ID);
          echo "'" . esc_js($trail->post_title) . "': '" . esc_js(ucfirst($status)) . "',";
        }
        ?>
      };

      const showTooltip = (shape, trail) => {
        const status = trailData[trail];
        const rect = shape.getBoundingClientRect();
        tooltip.innerText = `${trail}: ${status}`;
        tooltip.style.left = `${rect.left + rect.width / 2}px`;
        tooltip.style.top = `${rect.top - 30}px`;
        tooltip.style.display = 'block';
      };

      const hideTooltip = () => {
        tooltip.style.display = 'none';
      };

      shapes.forEach(shape => {
        const trail = shape.dataset.trail;
        const status = trailData[trail];

        shape.classList.add(status.toLowerCase());

        shape.addEventListener('mouseover', () => showTooltip(shape, trail));
        shape.addEventListener('mouseout', hideTooltip);
        shape.addEventListener('click', () => {
          showTooltip(shape, trail);
          setTimeout(hideTooltip, 3000);
        });
      });
    });
  </script>
</main><!-- #main -->

<?php
get_footer();
