<?php 

  class AM_Assets_ACF_Search {

    function __construct() {

      $this->setVars();
      $this->assetsLoading();

    }

    function setVars() {

     $this->path = get_template_directory_uri() . '/includes/extensions/acf-search/assets';

    }

    /* ---
      Styles & scripts
    --- */

      private function assetsLoading() {

        add_filter('admin_enqueue_scripts', [$this, 'loadStyles']); 

      }

      public function loadStyles() {

        wp_register_style('am-acf-search', $this->path . '/css/styles.css');
        wp_enqueue_style('am-acf-search');

      }

      public function loadScripts() {

      }

  }