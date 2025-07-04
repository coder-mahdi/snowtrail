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

  <div class="map-container" style="position: relative;">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/trail-map.jpg" usemap="#trailmap" alt="Trail Map" width="800">

    <map name="trailmap">
      <!-- Adjust these coords based on your image -->
      <area shape="rect" coords="100,100,200,200" href="#" data-trail="Orchard" alt="Orchard">
      <area shape="rect" coords="210,100,310,200" href="#" data-trail="South" alt="South">
      <area shape="rect" coords="320,100,420,200" href="#" data-trail="Village" alt="Village">
      <area shape="rect" coords="430,100,530,200" href="#" data-trail="Valley" alt="Valley">
      <area shape="rect" coords="540,100,640,200" href="#" data-trail="North" alt="North">
    </map>

    <!-- Tooltip --->
    <div id="tooltip" style="display:none; position:absolute; background:#333; color:#fff; padding:5px 10px; border-radius:4px; font-size:14px; pointer-events: none;"></div>
  </div>

  <style>
    .open { color: green; }
    .closed { color: red; }
  </style>

  <script>
    // JS object with trail statuses (from PHP)
    const trailData = {
      <?php
      foreach ($trails as $trail) {
        $status = get_field('status', $trail->ID);
        echo "'" . esc_js($trail->post_title) . "': '" . esc_js(ucfirst($status)) . "',";
      }
      ?>
    };

    const areas = document.querySelectorAll('area[data-trail]');
    const tooltip = document.getElementById('tooltip');

    const showTooltip = (e, trail) => {
      tooltip.innerText = trail + ': ' + trailData[trail];
      tooltip.style.display = 'block';
      tooltip.style.left = (e.pageX + 10) + 'px';
      tooltip.style.top = (e.pageY + 10) + 'px';
    };

    const hideTooltip = () => {
      tooltip.style.display = 'none';
    };

    areas.forEach(area => {
      const trail = area.dataset.trail;

      // Hover (Desktop)
      area.addEventListener('mouseover', (e) => showTooltip(e, trail));
      area.addEventListener('mousemove', (e) => {
        tooltip.style.left = (e.pageX + 10) + 'px';
        tooltip.style.top = (e.pageY + 10) + 'px';
      });
      area.addEventListener('mouseout', hideTooltip);

      // Click (Mobile / Touch)
      area.addEventListener('click', (e) => {
        e.preventDefault();
        showTooltip(e, trail);
        setTimeout(hideTooltip, 3000); // Hide after 3 seconds
      });
    });
  </script>
</main><!-- #main -->

<?php
get_footer();
