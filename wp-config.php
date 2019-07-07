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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'helawebd_wp632' );

/** MySQL database username */
define( 'DB_USER', 'helawebd_wp632' );

/** MySQL database password */
define( 'DB_PASSWORD', '58atS7p(1[' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'gevkwl0kcsxihjlqabo8or0nmqbewigiaxglmszjecru1kbz2zffhguo730g2o6d' );
define( 'SECURE_AUTH_KEY',  'k6fspvnke1vxtgbu3pp0so6837eppxoaq4hxedbvwosq0c9omhmcipczzegvrufe' );
define( 'LOGGED_IN_KEY',    'qfr2vvvdw5cbh517yvumgvf8ehvz0z42potoz5aobnldrbqhxvj2oiiw31fab5zu' );
define( 'NONCE_KEY',        '1fdbmtellrq5lquc6rx1jaw7zrbkxnhai5vjrb8bmjsmwikog4yl0bmjomp4kfnv' );
define( 'AUTH_SALT',        '23df1neh0j2xepmygp65z0amvkkepepnm7fbfnueo2hxccupyyfvqxefd4jx9xsh' );
define( 'SECURE_AUTH_SALT', 'shksrlxacxrgzovszbh8mbe1iuc7fhzekld9efa1oxkbocwxj0yoaspyscqetuj1' );
define( 'LOGGED_IN_SALT',   '9sporntdibe2ugxwkrsjewlzwgfnqwyu5c89htyfq28l7pj0518vntafuthzadac' );
define( 'NONCE_SALT',       '6ziksgwbed4q0c3bk80dfexya7hsb68yp1rim2e0i04nsmb5e8ac9i5yejmklqbp' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wphi_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
