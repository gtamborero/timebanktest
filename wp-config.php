<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'tbtest');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'M;Z^TXH-tRFRx/$ah<Lxi8C%u25EwH~+NVcUTq0detbbo_{.K&SKS/$WuXoaBqS)');
define('SECURE_AUTH_KEY', '#j3=;ZdF|XF=aFJ*H_l6g/Z+jwn~tVm6`K9q*(`GEk@)e];`t&+%U1a|^T~dczb~');
define('LOGGED_IN_KEY', 'X>joO:VGt1@?IO4_9]5v%.H6:/7B=v{x{T{VR/UPuN=i/g?ii/2?0-DY+F2h:uE@');
define('NONCE_KEY', 'i;Mp({v*LhRzau7.MebAM^Fb{2fX>a8c{+Mt=KyLxSfxUw2nsHpL@zAY<So`>;`}');
define('AUTH_SALT', '5-xGMEqXregbN&aWldy5UYejv+?j6KkuD_X3UtYaQt|b7lZ~]Os.P>EpgBRSa5%!');
define('SECURE_AUTH_SALT', '14#iitabT*!ZtVID:@mH1*J5CCHHT#v#fdn9RK*c1^ZsJ_2C/#>;:L(=N# )s-(D');
define('LOGGED_IN_SALT', 'Y&#Z&1lFVfn+8`U|0w e9FE^=H_Jg)@E-o#D|8x2?yGga>|-`#R4G9o`>+i {Ea+');
define('NONCE_SALT', '{j|uH*GLXTo[p@5lU[gq>0)=~~ s:-ZSdH.XC3`R>o6!49aWc`Mx%E &WK-vyvnk');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wptb_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

define('WP_LESS_COMPILATION', 'always');

define('WPLANG', 'en_US');  
//define( 'WPLANG', 'es_ES' );

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

