<div class="wrap am_acf_search">
  <h1 class="wp-heading-inline">
    <?php _e('ACF Search settings', 'am'); ?>
  </h1>
  <div class="am_acf_search__columns">
    <div class="am_acf_search__column">
      <form method="post">
        <table class="widefat">
          <thead>
            <tr>
              <th colspan="2">
                <?php _e('List of supported fields types:', 'am'); ?>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php $this->showFieldsList(); ?>
          </tbody>
        </table>
        <table class="widefat">
          <thead>
            <tr>
              <th colspan="2">
                <?php _e('Additional features:', 'am'); ?>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php $this->showFeaturesList(); ?>
          </tbody>
        </table>
        <input type="submit" class="button button-primary" name="am_acf_search_save" value="<?php _e('Save Changes', 'am'); ?>">
      </form>
    </div>
    <div class="am_acf_search__column">
      <table class="widefat">
        <thead>
          <tr>
            <th>
              <?php _e('How does this work?', 'am'); ?>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <p>
                <?php _e('This extension changes all SQL queries by extending the standard search to selected fields of Advanced Custom Fields PRO.', 'am'); ?>
              </p>
              <p>
                <?php _e('Advanced search works for WP_Query class.', 'am'); ?>
              </p>
              <p>
                <?php _e('On search page this works automatically.', 'am'); ?>
              </p>
              <p>
                <?php _e('For custom WP_Query loop also if you add <a href="https://codex.wordpress.org/Class_Reference/WP_Query#Search_Parameter" target="_blank">Search Parameter</a>.', 'am'); ?>
              </p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>