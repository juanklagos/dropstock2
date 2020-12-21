<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dropstock' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Zq9u7L2ZLA8HQ6nDkSQL6cPv2379G3cKVDsLQFjZdJ5XjJdOwEV2te0XkgPCBEQv' );
define( 'SECURE_AUTH_KEY',  'sluYf4HlLM3rwaub0GOWhc6PoaUp5OG8ZEq5ib5SIkmD0hIuFDwSiW1WTksp1AsW' );
define( 'LOGGED_IN_KEY',    'TqvoZHxtJkyzq3n7x4eJikLjB0W71mvMEabRmKDDdz0Kz5k4SKtgcgazcYsoJMFJ' );
define( 'NONCE_KEY',        'nacXwD43McRTSlIgOgLhDdCv0pxXRkFT9EmGcbACJxX1PoHRYAIBWkHVZtHYOrEN' );
define( 'AUTH_SALT',        'ccje431InC6b2QpFBwOvqPlfm3VCekufKZyF12tPg5ZXgmPRqTrHN29Ofj9rMjv8' );
define( 'SECURE_AUTH_SALT', '5U7DnKEN6m7EILykhBZuvcgITWTqwJF4bjaHBJKhNk55wRI7LVtJtqWoZ1Ou7E8I' );
define( 'LOGGED_IN_SALT',   '5Vgjh3FJ1JHvuzEtMjmWa4xB8nYxCWuLhzBMMdNcl1dssq7PRk6FNhupIqYHbRiP' );
define( 'NONCE_SALT',       '8B891Cm52p5yOPcSaPQ99FZcE7CPCyFVbNqTKyenTopZdvfNOs1yWSq9WImvCxeb' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
