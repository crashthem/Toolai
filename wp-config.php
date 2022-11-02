<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'e86ONQOBlv' );

/** Database username */
define( 'DB_USER', 'e86ONQOBlv' );

/** Database password */
define( 'DB_PASSWORD', '09tV1I2CMo' );

/** Database hostname */
define( 'DB_HOST', 'remotemysql.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'X8<-mC<>WpKtLTH*>bURkOIOt9r2d1J(n?*+>&;#pV&!H^*[=Y !:s;B`PVa*<Em' );
define( 'SECURE_AUTH_KEY',  '}2>d~?aZ^7AEI0{uW(=Pt=PkSVru;agLPJ_#6/Jj^F4S%7m(+&> *?h{h|^#F71-' );
define( 'LOGGED_IN_KEY',    'N#qzEVWS-^r4l}$|=OPRE+5E)P_;HK+j!_LD~s>+SfL?hk*/P%0bi^mgNV~[7*#{' );
define( 'NONCE_KEY',        ';11VQ8gdPC7I^[$0wGzW9b({m*W7;)BLMd4r2t82UD-je8r%g!iu+a36BXg`g}e*' );
define( 'AUTH_SALT',        '0+!Zw>!Z=3V40aENOzLf4H}GR9<eQ|Bp+Y2O|%^J[hx=>h0`s_bI3jb/?7:3.;?H' );
define( 'SECURE_AUTH_SALT', '.4Zj:pi-|B3[-l`_Fi6rkZ$a097_trR`mzV%&C@+3>T4bn2a}c7I+{`xz)`QBEm)' );
define( 'LOGGED_IN_SALT',   'y|zZT;FbOZ4,/5QOX+1A9fw*9uM2K2sy^NSB./:n&#Cy-FXtl!uVu]M31u&?^eIw' );
define( 'NONCE_SALT',       '}<y)-!yK&JOihHg4l~Mx0~<#l<)dRXe[ <[X0;R;.]!TTob~S/ rU@{3~ixZTsR?' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'FS_METHOD', 'direct');
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
