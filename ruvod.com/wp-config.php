<?php


// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'admin');

/** MySQL database password */
define('DB_PASSWORD', 'wordpress');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('AUTH_KEY',         'GOsvM(oj*iX|{n}-M%!xm6EL(k.NZD1lJrAr~#(.@&|Lcd#naOQ.w3br|:C1xz22');
define('SECURE_AUTH_KEY',  '-i#[G1oX:h9HeVj+FonqSVh&9i~?w}0jbrZ-8^eS&c^v=uV+U+&Q%CR`nJ$AU;L!');
define('LOGGED_IN_KEY',    'vCbmaVUuXmvl|9+xKjw?L4s-j,/$+/KcSLqwLM5>YOO}e@ut<7C-BmJ8/!3Qr+y>');
define('NONCE_KEY',        '0[n|f]kybM^K44j5Q69HN8oT+lO32Haj[Oo{<e`z<m>,|~Z+BIpl&/hQ-fIe3L-B');
define('AUTH_SALT',        'oJWx<r+U4Nem%-}oTS+Xx+:4dKUES7Oy}NAK6J6W&]O(36)0,30A[@4]]|`-?6Yg');
define('SECURE_AUTH_SALT', '$8U|ht5NqWz(5D:.J|%{XiO3OewM0?1Q1{g^=Y6d>a]J]W[`X2%4nd{(56Z}i^DT');
define('LOGGED_IN_SALT',   '2oB^td%;`q8C}I.a.zR)wf4^:jM#KFrgOFSz+um&}- 6f3T0 LIiS{e~J9VS/A6C');
define('NONCE_SALT',       'p|IC3?h~z+P+=%=cAD~og^3,6f{L]Z1q_Kp@Hcq4Y+si|q%]`++|wCt|7Y[~i8`b');


$table_prefix = 'wp_';





/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
