<?php

/*

Plugin Name: Castlegate IT WP Tweak Tool
Plugin URI: http://github.com/castlegateit/cgit-wp-tweak-tool
Description: Tweaks various parts of the WordPress dashboard.
Version: 1.3
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

use Cgit\TweakTool;

require __DIR__ . '/src/autoload.php';
require __DIR__ . '/functions.php';

/**
 * Load plugin
 */
add_action('init', function() {
    $tool = TweakTool::getInstance();
    $tool->update();
});
