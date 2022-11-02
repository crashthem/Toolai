<?php
global $am_option;

$am_option['shortname'] = "am";
$am_option['textdomain'] = "am";

// Functions
require get_parent_theme_file_path( '/includes/fn-core.php' );
require get_parent_theme_file_path( '/includes/fn-custom.php' );

// Extensions
require get_parent_theme_file_path( '/includes/extensions/breadcrumb-trail.php' );
require get_parent_theme_file_path( '/includes/extensions/shortcodes/shortcodes.php' );
require get_parent_theme_file_path( '/includes/extensions/acf-search/acf-search.php' );

/* Theme Init */
require get_parent_theme_file_path( '/includes/theme-widgets.php' );
require get_parent_theme_file_path( '/includes/theme-init.php' );
require get_parent_theme_file_path( '/includes/theme-cpt.php' );
require get_parent_theme_file_path( '/includes/theme-tax.php' );
require get_parent_theme_file_path( '/includes/theme-shortcodes.php' );
?>