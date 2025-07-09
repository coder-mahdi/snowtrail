<?php
/**
 * Snow Trail Status Update Form
 *
 * @package SnowTrail
 */

$success_message = '';

// Process form submission
if (isset($_POST['submit_status']) && isset($_POST['trail_status'])) {
  foreach ($_POST['trail_status'] as $trail_id => $new_status) {
    update_post_meta(intval($trail_id), 'status', sanitize_text_field($new_status));
  }
  $success_message = 'Status updated successfully!';
}

get_header();
?>

<main id="primary" class="site-main">
  <div class="snow-trail-form">
    <h1>Update Trail Status</h1>

    <?php if ($success_message): ?>
      <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" id="trail-status-form">
      <?php
      $trails = get_posts([
        'post_type' => 'trail',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
      ]);

      if (empty($trails)) {
        echo '<p>No trails found. Please create some trails first.</p>';
      } else {
        foreach ($trails as $trail) {
          $status = get_post_meta($trail->ID, 'status', true);
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
      }
      ?>
      <?php if (!empty($trails)): ?>
        <button type="submit" name="submit_status" class="submit-button" id="submit-btn">Save Status</button>
      <?php endif; ?>
    </form>
  </div>
</main>

<?php
get_footer();