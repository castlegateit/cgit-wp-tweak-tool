<?php

/*

Plugin Name: Castlegate IT WP Tweak Tool
Plugin URI: https://github.com/castlegateit/cgit-wp-tweak-tool
Plugin URI: https://github.com/castlegateit/cgit-wp-tweak-tool
Description: Tweaks various parts of the WordPress dashboard.
Version: 3.0.3
Author: Castlegate IT
Author URI: https://www.castlegateit.co.uk/
License: MIT
Network: True
Requires PHP: 5.6

*/

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

define('CGIT_TWEAK_TOOL_PLUGIN', __FILE__);

require_once __DIR__ . '/classes/autoload.php';

$plugin = new \Cgit\TweakTool\Plugin();

do_action('cgit_tweak_tool_plugin', $plugin);
do_action('cgit_tweak_tool_loaded');
