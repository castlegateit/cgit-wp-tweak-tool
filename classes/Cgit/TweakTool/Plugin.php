<?php

namespace Cgit\TweakTool;

class Plugin
{
    /**
     * Tweaks available
     *
     * @var array
     */
    private $tweaks = [
        'AdminBar',
        'Editor',
        'Menus',
        'Notifications',
        'Templates',
    ];

    /**
     * Constructor
     *
     * Load the configuration and loop through each tweak class and instantiate
     * each one to apply any tweaks specified in the configuration.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = new Config;

        foreach ($this->tweaks as $tweak) {
            $class = '\\Cgit\\TweakTool\\Tweaks\\' . $tweak;
            $instance = new $class($this->config);

            // Some tweaks depend on the identity of the current user, which
            // means they must run on or after the init action.
            add_action('init', [$instance, 'tweak']);
        }
    }
}
