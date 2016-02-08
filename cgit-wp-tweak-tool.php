<?php

/*

Plugin Name: Castlegate IT WP Tweak Tool
Plugin URI: http://github.com/castlegateit/cgit-wp-tweak-tool
Description: Tweaks various parts of the WordPress dashboard.
Version: 1.0
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

require __DIR__ . '/src/autoload.php';

/**
 * Load plugin
 */
add_action('plugins_loaded', function() {
    require __DIR__ . '/functions.php';

    // Initialization
    Cgit\TweakTool::getInstance();
});
