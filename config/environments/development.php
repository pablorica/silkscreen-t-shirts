<?php
/**
 * Configuration overrides for WP_ENV === 'development'
 */

use Roots\WPConfig\Config;

Config::define('SAVEQUERIES', true);
//Config::define('WP_DEBUG', true);
Config::define('WP_DEBUG', false);
Config::define('WP_DEBUG_DISPLAY', true);
Config::define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
Config::define('SCRIPT_DEBUG', true);

//ini_set('display_errors', '1');
/* Create a error log file */
ini_set('log_errors',TRUE);
ini_set('error_reporting', E_ALL);
ini_set('error_log', dirname(__FILE__) . '/../../web/error_log.txt');

// Enable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', false);

/**
 * LiteSpeed Cache Plugin
 */
Config::define('WP_CACHE', true);

/* wp-migrate */ 
//Error- Data contains characters which are invalid in the target database
Config::define( 'WPMDB_STRIP_INVALID_TEXT', true );