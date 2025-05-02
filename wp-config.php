<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sansartc_wp425' );

/** Database username */
define( 'DB_USER', 'sansartc_wp425' );

/** Database password */
define( 'DB_PASSWORD', 'S]3p[7CkC0' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',         'es7lf9vpdaxtcdry8r30sbeg8e2zc3ee5juud30m6skizdf7zspengssuzxsodmb' );
define( 'SECURE_AUTH_KEY',  'my0jtezfsjhl1ywblzs12uimyat98uhum6md9yxzoxjug5gudkrn405cz5uy8iku' );
define( 'LOGGED_IN_KEY',    'ibhaui0h46ldika7iwfxnfjrkwaf7adqlsf8swip9oooyxtaxbzxt1y5piqrujct' );
define( 'NONCE_KEY',        'ijedetnbzvvr1ulxiok5t6nbmbemdjej75xz3ygzrzeyzd9f4h6lyr79wvjmb1z2' );
define( 'AUTH_SALT',        'lrbz1wpngi7nxbr7oumrwx420kra49varzc5nzqfzi0ehj1btb0ot1xy2hner9ga' );
define( 'SECURE_AUTH_SALT', 'kpe8gxfgmomz9ns9mv2jkbg60f61h8lkdsnqrlyizjs4l5etg44edswozmo1zwgi' );
define( 'LOGGED_IN_SALT',   'kohkaesrhmuf0belvdxgysycoxrydxaotpfp1dablvkoqpr5lnnns4lrx63xvvjl' );
define( 'NONCE_SALT',       '1dcxwptkhmniypx2gyfq0p69pucmhujshjg97ac6cifetubmk0kvyswbegtdysnc' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
/*define( 'WP_DEBUG', true);
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );*/
/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

