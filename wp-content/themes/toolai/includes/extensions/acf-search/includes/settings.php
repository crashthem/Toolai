<?php

  class AM_Settings_ACF_Search {

    function __construct() {

      $this->setVars();
      $this->initActions();

    }

    private function setVars() {

      $this->fields = [
        'text'     => __('Text', 'am'),
        'textarea' => __('Text Area', 'am'),
        'number'   => __('Number', 'am'),
        'email'    => __('Email', 'am'),
        'url'      => __('Url', 'am'),
        'wysiwyg'  => __('Wysiwyg Editor', 'am'),
        'select'   => __('Select', 'am'),
        'checkbox' => __('Checkbox', 'am'),
        'radio'    => __('Radio Button', 'am')
      ];

      $this->features = [
        'whole_phrases' => __('Search for whole phrases instead of each single word of phrase', 'am'),
        'lite_mode'     => sprintf(__('Use lite mode %s(faster search, without checking field types)%s', 'am'), '<em>', '</em>')
      ];

      $this->path = get_template_directory_uri() . '/includes/extensions/acf-search/assets';

    }

    /* ---
      Admin menu
    --- */

      private function initActions() {

        add_action('admin_menu', [$this, 'addSettingsPage']);

      }

      public function addSettingsPage() {

        add_submenu_page(
          'options-general.php',
          'ACF Search',
          'ACF Search',
          'manage_options',
          'am_acf_search_admin_page',
          [$this, 'showSettingsPage']
        );

      }

    /* ---
      Settings page
    --- */

      public function showSettingsPage() {

        $this->getSelectedFieldsTypes();
        $this->getSelectedFeatures();

        require_once 'settings-page.php';

      }

      private function getSelectedFieldsTypes() {

        if (isset($_POST['am_acf_search_save']) && !get_option('am_acf_search_lite_mode', false)) {

          $this->selected = isset($_POST['am_acf_search_fields_types']) ? $_POST['am_acf_search_fields_types'] : [];
          $this->saveOption('am_acf_search_fields_types', $this->selected);

        } else {

          $this->selected = get_option('am_acf_search_fields_types', ['text', 'textarea', 'wysiwyg']);

        }

      }

      private function getSelectedFeatures() {

        foreach ($this->features as $key => $label) {

          if (isset($_POST['am_acf_search_save'])) {

            $this->$key = (isset($_POST['am_acf_search_features']) && in_array($key, $_POST['am_acf_search_features']));
            $this->saveOption('am_acf_search_' . $key, $this->$key);

          } elseif (get_option('am_acf_search_' . $key) !== false) {

            $this->$key = get_option('am_acf_search_' . $key);

          } else {

            $this->$key = false;

          }

        }

      }

    /* ---
      Save option
    --- */

      private function saveOption($key, $value) {

        if (get_option($key) !== false)
          update_option($key, $value);
        else
          add_option($key, $value);

      }

    /* ---
      Options on settings page
    --- */

      private function showFieldsList() {

        foreach ($this->fields as $key => $label) {

          $id         = 'am_acf_search_fields_' . $key;
          $isChecked  = in_array($key, $this->selected) ? 'checked' : '';
          $isDisabled = $this->lite_mode ? 'disabled' : '';
          
          ?>
            <tr>
              <td>
                <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
              </td>
              <td>
                <input type="checkbox" id="<?php echo $id; ?>" name="am_acf_search_fields_types[]" value="<?php echo $key; ?>" <?php echo $isChecked . ' ' . $isDisabled ?>>
              </td>
            </tr>
          <?php

        }

      }

      private function showFeaturesList() {

        foreach ($this->features as $key => $label) {

          $id        = 'am_acf_search_' . $key;
          $isChecked = $this->$key ? 'checked="checked"' : '';
          
          ?>
            <tr>
              <td>
                <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
              </td>
              <td>
                <input type="checkbox" id="<?php echo $id; ?>" name="am_acf_search_features[]" value="<?php echo $key; ?>" <?php echo $isChecked ?>>
              </td>
            </tr>
          <?php

        }

      }

  }