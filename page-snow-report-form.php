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
		<h1>Update Trail Status</h1>

		<form method="post">
			<?php
			$trails = get_posts([
				'post_type' => 'trail',
				'numberposts' => '-1',
				'orderby' => 'title',
				'order' => 'ASC'
			]);

			foreach ($trails as $trail) {
				$status = get_field('status', $trail->ID);
				?>
				<label for="trail_<?php echo $trail->ID; ?>">
					<?php echo esc_html($trail->post_title); ?>
				</label>
				  <select name="trail_status[<?php echo $trail->ID; ?>]" id="trail_<?php echo $trail->ID; ?>">
                    <option value="open" <?php selected($status, 'open'); ?>>Open</option>
                    <option value="closed" <?php selected($status, 'closed'); ?>>Closed</option>
                  </select>
				  <br><br>
                <?php

			}
			 ?>
		</form>

	  <?php
       if (isset($_POST['submit_status']) && isset($_POST['trail_status'])) {
       foreach ($_POST['trail_status'] as $trail_id => $new_status) {
       update_field('status', $new_status, intval($trail_id));
    }
    echo "<p style='color: green;'>Status updated!</p>";
  }
  ?>

	</main><!-- #main -->

<?php
get_footer();
