<?php
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
define( 'DB_NAME', 'test_task' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'MySQL-8.2' );

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
define( 'AUTH_KEY',         'aNK]V)=UQE7PWOglPMAhh:jrP<g`.IQ@ Q$7Xj3p4`^+M5Pip0}3y@X.e~udPHvu' );
define( 'SECURE_AUTH_KEY',  '[add(J?i(8%~Tv=NE[nqq{{N[xgMIZ9tt=}Lih]HsqkmvG1(xeO-r0]q)S%{%`eY' );
define( 'LOGGED_IN_KEY',    's]wSty~|jSz@MhzuS6BLA9s(ET897S<2(`M@N*II%tq/Y=+)4y5F I0@?<Vqmc1O' );
define( 'NONCE_KEY',        'Uf9u[b8=F%(6XHo>{vRV]Y74sr7sAec5M0@F|n<A%qo%Oig/I Rid2w3xJ})_)<r' );
define( 'AUTH_SALT',        'k#vmeb#L8{*DS#S@(zt:yx]A@]E8S,1w8qBqmFk#aZ(:b3&SOg`SzV&MhRS&.~ZI' );
define( 'SECURE_AUTH_SALT', 't1p&M2w*<e;ng4hz5X,KPWI;/J==4K:iDnO/F35#kpmBg&F,9TB6T~_Pqc,3q!f-' );
define( 'LOGGED_IN_SALT',   '+Br>m0[8@3UGYwd,r>olWjPd^%y;JcEdW%~k>@9S71h!.*Cn,r!=XM,0<!-2J F0' );
define( 'NONCE_SALT',       'Idn*z+9WrRmi}{{+EX>4dU?bz6A?<r2J?hG6-XIfJ8r#6JAF+9He*ceGMMp d>Wh' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
