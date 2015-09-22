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
define('DB_NAME', 'timeous');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'h]CGzg+EuZ%)k+/247c!`Wr|-nR+m+kIq3`x.k?s<oc`gE&x!a[+#v2&SKuryU`u');
define('SECURE_AUTH_KEY',  '++^zqd_UO2qxr|R}gR7p4<Ml0kZXBbWt?@! 9]aixpg>|cL7SB}G.a_iqy(|[1be');
define('LOGGED_IN_KEY',    '_Ji]&&MXffT{9I0<$=><swbJ-5+<rpO,ZnM6*-FbX|{MBUftE?--5L`9sK#yphd-');
define('NONCE_KEY',        'VXM&|M+3r6FCI?:e^`4!_{8yr+4u@7`1bpM.96lXQ`w!SG`1{`TlN2>[?}44~:tM');
define('AUTH_SALT',        'm1%+ sze< s3dP&yTE3ib#L$I.;uw/Y1~U<IW 3C7jr0`VG;kC-h#%[+W*`DAI0+');
define('SECURE_AUTH_SALT', 'GpTA3Hc3p)1=op*W]Fvd#D$[`Msqm6:a4:QJx!eYPUn8zN72Au6e<T*p>Ac[Pd{L');
define('LOGGED_IN_SALT',   'o|f)$&w}SA;`|d|;-7+>rU$/8My2`!Yl*9#%M4I<|SsD>,L>&M>}IYV1j!Z1`/??');
define('NONCE_SALT',       '8dT$gf3B)jB?3AjRZd6X(|=z+gwT|XejERkI|X!I>5GbK!zFGq,o-df-A>zca[a]');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');