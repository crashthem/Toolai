<?php

  class AM_ACF_Search {

    function __construct() {

      $this->init();

    }

    private function init() {

      add_filter('wp_loaded', [$this, 'selectCore']);

    }

    public function selectCore() {

      $isAjax = defined('DOING_AJAX') && DOING_AJAX;

      if ( !is_admin() || $isAjax ) {

        $this->loadSearchCore();

      } 

      else {

        $this->loadAdminCore();

      }

    }

    private function loadSearchCore() {

      $this->loadClass('Search');

    }

    private function loadAdminCore() {

      $this->loadClass('Assets');

      if (!is_network_admin())
        $this->loadClass('Settings');

    }

    private function loadClass($class) {

      $var   = strtolower($class);
      $class = 'AM_' . $class . '_ACF_Search';

      require_once $var . '.php';

      $this->$var = new $class();

    }

  }