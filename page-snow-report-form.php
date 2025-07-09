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

if (isset($_POST['submit_status']) && isset($_POST['trail_status'])) {
  foreach ($_POST['trail_status'] as $trail_id => $new_status) {
    update_field('status', sanitize_text_field($new_status), intval($trail_id));
  }

  wp_redirect(add_query_arg('updated', 'true', get_permalink()));
  exit;
}

get_header();
?>

<main id="primary" class="site-main">
  <div class="snow-trail-form">
    <h1>Update Trail Status</h1>

    <?php if (isset($_GET['updated']) && $_GET['updated'] === 'true'): ?>
      <div class="success-message">Status updated successfully!</div>
    <?php endif; ?>

    <form method="post">
      <?php
      $trails = get_posts([
        'post_type' => 'trail',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
      ]);

      foreach ($trails as $trail) {
        $status = get_field('status', $trail->ID);
        ?>
        <div class="trail-item">
          <label for="trail_<?php echo $trail->ID; ?>">
            <?php echo esc_html($trail->post_title); ?>
          </label>
          <select name="trail_status[<?php echo $trail->ID; ?>]" id="trail_<?php echo $trail->ID; ?>">
            <option value="open" <?php selected($status, 'open'); ?>>Open</option>
            <option value="closed" <?php selected($status, 'closed'); ?>>Closed</option>
          </select>
        </div>
        <?php
      }
      ?>
      <button type="submit" name="submit_status" class="submit-button">Save Status</button>
    </form>
  </div>
</main>

<?php
get_footer();